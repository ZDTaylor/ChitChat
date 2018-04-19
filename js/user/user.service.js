(function () {
    'use strict';

    angular
        .module('app.users')
        .factory('userservice', userservice);

    userservice.inject = ['$resource', 'apiUrl', 'User'];
    function userservice($resource, apiUrl, User) {
        var service = {
            user: new User(),
            register: register,
            login: login,
            logout: logout,
            resetPasswordEmail: resetPasswordEmail,
            resetPasswordReset: resetPasswordReset,
            deleteAccount: deleteAccount,
            ban: ban,
            suspend: suspend
        };

        return service;

        ////////////////
        function register(email, password) {
            var Register = $resource(apiUrl + 'register.php');

            return Register.save({ email: email, password: password }).$promise;
        }

        function login(email, password) {
            var Login = $resource(apiUrl + 'login.php');

            return Login.save({ email: email, password: password }).$promise
                .then(
                    function (response) {
                        if (response.success == true) {
                            service.user = new User(response.user);
                        }
                        return response;
                    });
        }

        function logout() {
            var Logout = $resource(apiUrl + 'logout.php');

            return Logout.get().$promise
                .then(
                    function (response) {
                        if (response.success == true) {
                            service.user = new User();
                        }
                        return response;
                    });
        }

        function resetPasswordEmail(email) {
            var ResetPasswordEmail = $resource(apiUrl + 'resetpasswordemail.php');

            return ResetPasswordEmail.save({ email: email }).$promise;
        }

        function resetPasswordReset(key, newpassword) {
            var ResetPasswordReset = $resource(apiUrl + 'resetpasswordreset.php');

            return ResetPasswordReset.save({ key: key, newpassword: newpassword }).$promise;
        }

        function deleteAccount() {
            var DeleteAccount = $resource(apiUrl + 'deleteaccount.php');

            return DeleteAccount.get().$promise
                .then(
                    function (response) {
                        if (response.success == true) {
                            service.user = new User();
                        }
                        return response;
                    });
        }

        function ban(userID) {
            var Ban = $resource(apiUrl + 'ban.php');

            return Ban.save({ userID: userID }).$promise;
        }

        function suspend(userID, datetime) {
            var Suspend = $resource(apiUrl + 'suspend.php');

            return Suspend.save({ userID: userID, datetime: datetime }).$promise;
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