(function () {
    'use strict';

    angular
        .module('app.users')
        .factory('User', User);

    function User() {
        function UserConstructor(args) {
            if (typeof (args) == 'undefined') {
                args = {};
            }
            var user = this;
            user = {
                userID: args.userID || null,
                email: args.email || null,
                displayName: args.displayName || null,
                isAdmin: args.isAdmin || null,
                banned: args.banned || null,
                suspended: args.suspended || null
            };

            return user;
        }

        return UserConstructor;

        ////////////////

    }
})();