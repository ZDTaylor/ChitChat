(function () {
    'use strict';

    angular
        .module('app')
        .controller('MessageScrollController', MessageScrollController);

    MessageScrollController.inject = ['userservice', 'messageservice', '$interval', 'Message', '$scope'];
    function MessageScrollController(userservice, messageservice, $interval, Message, $scope) {
        var vm = this;
        vm.userservice = userservice;
        vm.load = loadMessages;
        vm.displayName = displayName;
        vm.messages = [];

        vm.load();

        ////////////////

        function loadMessages() {
            if (typeof (EventSource) === "undefined") { //Polling
                $interval(function () {
                    messageservice.load()
                        .then(
                            function (response) {
                                if (response.success === true) {
                                    var j = 0;
                                    for (var i = 0; i < messageservice.messages.length; i++) {

                                        // in vm but not messageservice -> delete
                                        // in both -> update
                                        // in messageservice but not bm -> add
                                        if (j < vm.messages.length && messageservice.messages[i].messageID === vm.messages[j].messageID) {
                                            updateMessage(i, j);
                                        }
                                        else if (j < vm.messages.length && messageservice.messages[i].messageID !== vm.messages[j].messageID) {
                                            do {
                                                vm.messages.splice(j, 1);
                                            } while (messageservice.messages[i].messageID !== vm.messages[j].messageID);
                                            updateMessage(i, j);
                                        }
                                        else {
                                            var message = new Message(messageservice.messages[i]);
                                            vm.messages.push(message);
                                        }
                                        j++;
                                    }
                                }
                            })
                        .catch(
                            function (response) {

                            });
                }, 1000);
            }
            else { // Server-Side Event
                vm.source = messageservice.loadStream();
                vm.source.onmessage = function (event) {
                    $scope.$apply(function () {
                        var response = angular.fromJson(event.data);
                        console.log(response);
                        if (response.success == true) {
                            var j = 0;
                            for (var i = 0; i < response.messages.length; i++) {

                                // in vm but not messageservice -> delete
                                // in both -> update
                                // in messageservice but not bm -> add
                                if (j < vm.messages.length && response.messages[i].messageID === vm.messages[j].messageID) {
                                    vm.messages[j].content = response.messages[i].content;
                                    vm.messages[j].mentions = response.messages[i].mentions;
                                    vm.messages[j].net_likes = response.messages[i].net_likes;
                                    vm.messages[j].reaction = response.messages[i].reaction;
                                }
                                else if (j < vm.messages.length && response.messages[i].messageID !== vm.messages[j].messageID) {
                                    do {
                                        vm.messages.splice(j, 1);
                                    } while (response.messages[i].messageID !== vm.messages[j].messageID);
                                    vm.messages[j].content = response.messages[i].content;
                                    vm.messages[j].mentions = response.messages[i].mentions;
                                    vm.messages[j].net_likes = response.messages[i].net_likes;
                                    vm.messages[j].reaction = response.messages[i].reaction;
                                }
                                else {
                                    var message = new Message(response.messages[i]);
                                    vm.messages.push(message);
                                }
                                j++;
                            }
                        }
                    });
                };
            }
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