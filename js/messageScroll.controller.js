(function () {
    'use strict';

    angular
        .module('app')
        .controller('MessageScrollController', MessageScrollController);

    MessageScrollController.inject = ['userservice', 'messageservice', '$interval', 'Message', '$scope', '$rootScope', 'modalservice', '$timeout'];
    function MessageScrollController(userservice, messageservice, $interval, Message, $scope, $rootScope, modalservice, $timeout) {
        var vm = this;
        vm.userservice = userservice;
        vm.load = load;
        vm.displayName = userservice.displayName;
        vm.like = like;
        vm.dislike = dislike;
        vm.remove = remove;
        vm.ban = ban;
        vm.suspend = suspend;
        vm.quote = quote;
        vm.mention = mention;
        vm.edit = edit;
        vm.messages = [];
        vm.fallback = false;
        vm.showFallback = false;
        vm.fallbackEnable = fallbackEnable;

        $timeout(function () {
            vm.showFallback = true;
        }, 5000);

        vm.load();

        ////////////////

        function load() {
            if (typeof (EventSource) === "undefined" || vm.fallback === true) { //Polling
                if (typeof (vm.source) !== "undefined") {
                    vm.source.target.close();
                }
                $interval(function () {
                    messageservice.load()
                        .then(
                            function (response) {
                                if (response.success === true) {
                                    updateMessageArray(response);
                                }
                            });
                }, 1000);
            }
            else { // Server-Side Event
                vm.source = messageservice.loadStream();
                vm.source.onmessage = function (event) {
                    $scope.$apply(function () {
                        var response = angular.fromJson(event.data);
                        if (response.success == true) {
                            updateMessageArray(response);
                        }
                    });
                };
            }
        }

        function like(message) {
            messageservice.like(message.messageID)
                .then(function (response) {
                    if (!response.success) {
                        modalservice.openGeneralModal('Error', 'Please try again.');
                    }
                })
                .catch(function (response) {
                    modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                });
        }

        function dislike(message) {
            messageservice.dislike(message.messageID)
                .then(function (response) {
                    if (!response.success) {
                        modalservice.openGeneralModal('Error', 'Please try again.');
                    }
                })
                .catch(function (response) {
                    modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                });
        }

        function remove(message) {
            var modal = modalservice.openConfirmModal('Are you sure?', 'Deleting a message cannot be undone.');

            modal.result
                .then(function (response) {
                    messageservice.remove(message.messageID)
                        .then(function (response) {
                            if (!response.success) {
                                modalservice.openGeneralModal('Error', 'Please try again.');
                            }
                        })
                        .catch(function (response) {
                            modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                        });
                });
        }

        function ban(message) {
            var modal = modalservice.openConfirmModal('Are you sure?', 'Banning a user is a serious action that should not be taken lightly.  This action cannot be undone.');

            modal.result
                .then(function (response) {
                    userservice.ban(message.poster)
                        .then(function (response) {
                            if (response.success) {
                                modalservice.openGeneralModal('Success', 'The user is now banned.');
                            }
                            else {
                                modalservice.openGeneralModal('Error', 'Please try again.');
                            }
                        })
                        .catch(function (response) {
                            modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                        });
                });
        }

        function suspend(message) {
            var modal = modalservice.openSuspendModal();

            modal.result
                .then(function (response) {
                    var datetime = new Date(Date.UTC(response.date.getFullYear(), response.date.getMonth(), response.date.getDate()));
                    datetime = Math.floor(datetime.getTime() / 1000);
                    userservice.suspend(message.poster, datetime)
                        .then(function (response) {
                            if (response.success) {
                                modalservice.openGeneralModal('Success', 'The user is now suspended.');
                            }
                            else {
                                modalservice.openGeneralModal('Error', 'Please try again.');
                            }
                        })
                        .catch(function (response) {
                            modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                        });
                });
        }

        function quote(message) {
            $rootScope.$broadcast("quoteMessage", message);
        }

        function mention(message) {
            $rootScope.$broadcast("mentionUser", message.poster);
        }

        function edit(message) {
            $rootScope.$broadcast("editMessage", message);
        }

        function fallbackEnable() {
            vm.fallback = true;
            vm.load();
        }

        function updateMessageArray(response) {
            var j = 0;
            for (var i = 0; i < response.messages.length; i++) {

                // in vm but not response -> delete
                // in both -> update
                // in response but not bm -> add
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
    }
})();