(function () {
    'use strict';

    angular
        .module('app')
        .controller('MessageBoxController', MessageBoxController);

    MessageBoxController.inject = ['$rootScope', '$scope', 'userservice', 'messageservice', 'Message'];
    function MessageBoxController($rootScope, $scope, userservice, messageservice, Message) {
        var vm = this;
        vm.message = new Message({ poster: userservice.user.id });
        vm.error = false;
        vm.postMessage = postMessage;
        vm.mentionUser = mentionUser;
        vm.quoteMessage = quoteMessage;

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
    }
})();