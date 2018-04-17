(function () {
    'use strict';

    angular
        .module('app')
        .controller('MessageScrollController', MessageScrollController);

    MessageScrollController.inject = ['userservice', 'messageservice', '$interval'];
    function MessageScrollController(userservice, messageservice, $interval) {
        var vm = this;
        vm.load = loadMessages;
        vm.displayName = displayName;

        var load = $interval(vm.load, 1000);

        ////////////////

        function loadMessages() {
            messageservice.load()
                .then(
                    function (response) {
                        if (response.success === true) {
                            vm.messages = messageservice.messages;
                            console.log(vm.messages);
                        }
                    })
                .catch(
                    function (response) {

                    });
        }

        function displayName(userID) {
            // https://gist.github.com/endel/321925f6cafa25bbfbde
            Number.prototype.pad = function (size) {
                var s = String(this);
                while (s.length < (size || 2)) { s = "0" + s; }
                return s;
            }

            return "Anonymous#" + userID.pad(8);
        }
    }
})();