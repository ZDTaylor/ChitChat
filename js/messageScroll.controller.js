(function () {
    'use strict';

    angular
        .module('app')
        .controller('MessageScrollController', MessageScrollController);

    MessageScrollController.inject = ['userservice', 'messageservice', '$interval', 'Message', '$scope', '$rootScope', 'modalservice'];
    function MessageScrollController(userservice, messageservice, $interval, Message, $scope, $rootScope, modalservice) {
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

        vm.load();

        ////////////////

        function load() {
            if (true) {//typeof (EventSource) === "undefined") { //Polling
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
                                            vm.messages[j].content = messageservice.messages[i].content;
                                            vm.messages[j].mentions = messageservice.messages[i].mentions;
                                            vm.messages[j].net_likes = messageservice.messages[i].net_likes;
                                            vm.messages[j].reaction = messageservice.messages[i].reaction;
                                        }
                                        else if (j < vm.messages.length && messageservice.messages[i].messageID !== vm.messages[j].messageID) {
                                            do {
                                                vm.messages.splice(j, 1);
                                            } while (messageservice.messages[i].messageID !== vm.messages[j].messageID);
                                            vm.messages[j].content = messageservice.messages[i].content;
                                            vm.messages[j].mentions = messageservice.messages[i].mentions;
                                            vm.messages[j].net_likes = messageservice.messages[i].net_likes;
                                            vm.messages[j].reaction = messageservice.messages[i].reaction;
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
                        if (response.success == true) {
                            console.log(response);
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

        function like(message) {
            messageservice.like(message.messageID)
                .then(function (response) {
                    if (response.success) {
                        // success
                    }
                    else {
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
                    if (response.success) {
                        //success
                    }
                    else {
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
                            if (response.success) {
                                //success
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
    }
})();