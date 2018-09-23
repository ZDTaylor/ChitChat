(function () {
    'use strict';

    angular
        .module('app')
        .controller('MessageBoxController', MessageBoxController)
        .directive("autoGrow", AutoGrowDirective);

    MessageBoxController.inject = ['$scope', 'userservice', 'messageservice', 'Message', 'modalservice'];
    function MessageBoxController($scope, userservice, messageservice, Message, modalservice) {
        var vm = this;
        vm.message = new Message({ poster: userservice.user.userID });
        vm.messageContent = "";
        vm.mentions = [];
        vm.editing = false;
        vm.post = post;
        vm.mention = mention;
        vm.quote = quote;
        vm.edit = edit;
        vm.shiftEnter = shiftEnter;
        vm.removeMention = removeMention;
        vm.scroll = scroll;
        vm.displayName = userservice.displayName;

        $scope.$on("quoteMessage", vm.quote);
        $scope.$on("mentionUser", vm.mention);
        $scope.$on("editMessage", vm.edit);
        ////////////////

        function post() {
            vm.message.poster = userservice.user.userID;
            vm.message.content = vm.messageContent;
            vm.message.mentions = vm.mentions;
            if (vm.editing === true) {
                messageservice.edit(vm.message)
                    .then(
                        function (response) {
                            if (response.success === true) {
                                vm.editing = false;
                                vm.messageContent = "";
                                vm.mentions = [];
                                vm.message.messageID = response.messageID;
                                messageservice.messages.push(vm.message);
                                vm.message = new Message({ poster: userservice.user.userID });
                                messageservice.load();
                            }
                            else {
                                if (userservice.user.userID == null) {
                                    modalservice.openGeneralModal('Error', 'Please log in and try again.');
                                }
                                else {
                                    modalservice.openGeneralModal('Error', 'Please check your message and try again.');
                                }
                            }
                        })
                    .catch(
                        function (response) {
                            modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                        });
            }
            else {
                messageservice.post(vm.message)
                    .then(
                        function (response) {
                            if (response.success === true) {
                                vm.editing = false;
                                vm.messageContent = "";
                                vm.mentions = [];
                                vm.message.messageID = response.messageID;
                                messageservice.messages.push(vm.message);
                                vm.message = new Message({ poster: userservice.user.userID });
                                messageservice.load();
                            }
                            else {
                                modalservice.openGeneralModal('Error', 'Please check your message and try again.');
                            }
                        })
                    .catch(
                        function (response) {
                            modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                        });
            }

        }

        function mention(event, poster) {
            if (vm.mentions.indexOf(poster) === -1 && poster !== userservice.user.userID) {
                vm.mentions.push(poster);
            }
        }

        function quote(event, message) {
            vm.mention(null, message.poster);
            vm.messageContent = "[QUOTE]" + userservice.displayName(message.poster) + " said:\n" + message.content + "[/QUOTE]\n------------\n\n" + vm.messageContent;

        }

        function edit(event, message) {
            vm.message = new Message(message);
            vm.messageContent = message.content;
            vm.mentions = message.mentions;
            vm.editing = true;
        }

        function shiftEnter($event) {
            if ($event.keyCode === 13 && !$event.shiftKey) {
                $event.preventDefault();
                vm.post();
            }
        }

        function removeMention(mention) {
            var index = vm.mentions.indexOf(mention);
            if (index !== -1) {
                vm.mentions.splice(index, 1);
            }
        }

        function scroll() {
            var body = document.body;
            body.scrollTop = body.scrollHeight;
        }
    }
    //https://gist.github.com/enapupe/2a59589168f33ca405d0
    function AutoGrowDirective() {
        return {
            require: 'ngModel',
            link: function (scope, element, attr, ngModel) {
                var update = function () {
                    var messageBox = angular.element(document.querySelector("#cc-messageBox"));
                    messageBox.parent().css("padding-bottom", "32px");

                    element.css("height", "32px");
                    var height = element[0].scrollHeight;

                    var parentHeight = parseFloat(angular.element(window).height());
                    var messageBoxPercent = 0.25;

                    var maxHeight = Math.floor((parentHeight * messageBoxPercent) - 16);

                    if (height > 0) {
                        if (height < maxHeight) {
                            element.css("height", height + "px");
                            messageBox.parent().css("padding-bottom", height + "px");
                        }
                        else {
                            element.css("height", maxHeight + "px");
                            messageBox.parent().css("padding-bottom", maxHeight + "px");
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