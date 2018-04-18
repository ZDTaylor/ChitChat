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
            resetpasswordemail: resetpasswordemail,
            resetpasswordreset: resetpasswordreset,
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

        function resetpasswordemail(email) {
            var ResestPasswordEmail = $resource(apiUrl + 'resetpasswordemail.php');

            return ResetPasswordEmail.save({ email: email }).$promise;
        }

        function resetpasswordreset(key, newpassword) {
            var ResestPasswordReset = $resource(apiUrl + 'resetpasswordreset.php');

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

        function ban(userid) {
            var Ban = $resource(apiUrl + 'ban.php');

            return Ban.save({ userid: userid }).$promise;
        }

        function suspend(userid, datetime) {
            var Suspend = $resource(apiUrl + 'suspend.php');

            return Suspend.save({ userid: userid, datetime: datetime }).$promise;
        }
    }
})();