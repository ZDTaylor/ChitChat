(function () {
    'use strict';

    angular
        .module('app')
        .controller('NavController', NavController);

    NavController.inject = ['userservice'];
    function NavController(userservice) {
        var vm = this;
        vm.user = userservice.user;
        vm.error = false;
        vm.dropdownMessage = vm.user.email || "Login";
        vm.email = vm.email || "";
        vm.passwd = vm.passwd || "";
        vm.dropdownIsOpen = false;
        vm.login = login;
        vm.register = register;
        vm.logout = logout;
        vm.deleteAccount = deleteAccount;

        vm.login();
        ////////////////

        function register() {
            userservice.register(vm.email, vm.passwd)
                .then(
                    function (response) { // HTTP Success
                        if (response.success === true) {
                            vm.error = false;
                        }
                        else {
                            vm.error = true;
                        }
                    })
                .catch(
                    function (response) { // HTTP Failure
                        vm.error = true;
                    });
        }

        function login() {
            userservice.login(vm.email, vm.passwd)
                .then(
                    function (response) { // HTTP Success
                        if (response.success === true) {
                            vm.user = userservice.user;
                            vm.email = "";
                            vm.passwd = "";
                            vm.dropdownMessage = vm.user.email;
                            vm.dropdownIsOpen = false;
                            vm.error = false;
                        }
                        else {
                            vm.error = true;
                        }
                    })
                .catch(
                    function (response) { // HTTP Failure
                        vm.error = true;
                    });
        }

        function logout() {
            userservice.logout()
                .then(
                    function (response) {
                        if (response.success === true) {
                            vm.error = false;
                            vm.user = userservice.user;
                            vm.dropdownMessage = "Login";
                            vm.dropdownIsOpen = false;
                        }
                        else {
                            vm.error = true;
                        }
                    })
                .catch(
                    function (response) {
                        vm.error = true;
                    });
        }

        function deleteAccount() {
            userservice.deleteAccount()
                .then(
                    function (response) {
                        if (response.success === true) {
                            vm.error = false;
                            vm.user = userservice.user;
                            vm.dropdownMessage = "Login";
                            vm.dropdownIsOpen = false;
                        }
                        else {
                            vm.error = true;
                        }
                    })
                .catch(
                    function (response) {
                        vm.error = true;
                    });
        }

    }
})();