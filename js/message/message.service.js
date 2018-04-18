(function () {
    'use strict';

    angular
        .module('app.messages')
        .factory('messageservice', messageservice);

    messageservice.inject = ['$resource', 'apiUrl'];
    function messageservice($resource, apiUrl) {
        var service = {
            messages: [],
            load: load,
            post: post,
            edit: edit,
            remove: remove,
            like: like,
            dislike: dislike
        };

        return service;

        ////////////////
        function load() {
            var Load = $resource(apiUrl + 'load.php');

            return Load.get().$promise
                .then(
                    function (response) {
                        if (response.success == true) {
                            service.messages = response.messages;
                        }
                        return response;
                    });
        }

        function loadStream() {
            service.source = new EventSource(apiUrl + 'load.stream.php');

            service.source.onmessage = function (event) {
                $scope.$apply(function () {
                    var response = angular.fromJson(event.data);
                    if (response.success == true) {
                        service.messages = response.messages;
                    }
                });
            };

            return service.source;
        }

        function post(message) {
            var Post = $resource(apiUrl + 'post.php');

            return Post.save({ message: message }).$promise;
        }

        function edit(message) {
            var Edit = $resource(apiUrl + 'edit.php');

            return Edit.save({ message: message }).$promise;
        }

        function remove(messageid) {
            var Remove = $resource(apiUrl + 'remove.php');

            return Remove.save({ messageid: messageid }).$promise;
        }

        function like(messageid) {
            var Like = $resource(apiUrl + 'like.php');

            return Like.save({ messageid: messageid }).$promise;
        }

        function dislike(messageid) {
            var Dislike = $resource(apiUrl + 'dislike.php');

            return Dislike.save({ messageid: messageid }).$promise;
        }
    }
})();