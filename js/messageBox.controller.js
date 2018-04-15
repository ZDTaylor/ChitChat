(function () {
    'use strict';

    angular
        .module('app')
        .controller('MessageBoxController', MessageBoxController)
        .directive("autoGrow", AutoGrowDirective);

    MessageBoxController.inject = ['$rootScope', '$scope', 'userservice', 'messageservice', 'Message'];
    function MessageBoxController($rootScope, $scope, userservice, messageservice, Message) {
        var vm = this;
        vm.message = new Message({ poster: userservice.user.id });
        vm.error = false;
        vm.postMessage = postMessage;
        vm.mentionUser = mentionUser;
        vm.quoteMessage = quoteMessage;
        vm.shiftEnter = shiftEnter;

        ////////////////

        function postMessage() {
            vm.message.poster = userservice.user.id;
            messageservice.post(vm.message)
                .then(
                    function (response) {
                        if (response.success === true) {
                            vm.error = false;
                            vm.message.id = response.id;
                            messageservice.messages.push(vm.message);
                            vm.message = new Message(poster = userservice.user.id);
                            messageservice.load();
                        }
                    })
                .catch(
                    function (response) {
                        vm.error = true;
                    });
        }

        function mentionUser(id) {
            vm.message.mentionUser(id);
        }

        function quoteMessage(message) {
            vm.message.content = "[QUOTE]" + message.content + "[/QUOTE]\n" + vm.message.content;
            vm.mentionUser(message.poster);
        }

        function shiftEnter($event) {
            if ($event.keyCode === 13 && !$event.shiftKey) {
                $event.preventDefault();
                vm.postMessage();
            }
        }
    }
    //https://gist.github.com/enapupe/2a59589168f33ca405d0
    function AutoGrowDirective() {
        return {
            require: 'ngModel',
            link: function (scope, element, attr, ngModel) {
                var update = function () {
                    element.css("height", "32px");
                    var height = element[0].scrollHeight;
                    var messageBox = angular.element(document.querySelector("#cc-messageBox"));

                    var parentHeight = parseFloat(messageBox.parent().css("height"));
                    var messageBoxPercent = parseFloat(messageBox.css("max-height")) / 100.0;

                    var maxHeight = Math.floor((parentHeight * messageBoxPercent) - 16);

                    if (height > 0) {
                        if (height < maxHeight) {
                            element.css("height", height + "px");
                        }
                        else {
                            element.css("height", maxHeight + "px");
                        }
                    }
                };
                scope.$watch(function () {
                    return ngModel.$modelValue;
                }, function (newval) {
                    update();
                }, true);
                attr.$set("ngTrim", "false");
            }
        }
    }
})();