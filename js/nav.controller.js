(function () {
    'use strict';

    angular
        .module('app')
        .controller('NavController', NavController);

    NavController.inject = ['userservice'];
    function NavController(userservice) {
        var vm = this;
        vm.user = userservice.user;
        vm.dropdownMessage = vm.user ? vm.user.email : "Login";
        vm.email = vm.email || "";
        vm.passwd = vm.passwd || "";
        vm.dropdownIsOpen = false;
        vm.login = login;
        vm.register = register;
        vm.logout = logout;

        ////////////////

        function login() {
            userservice.login(vm.email, vm.passwd)
                .then(
                    function (response) { // HTTP Success
                        console.log("HTTP suucess");
                        console.log("Login success: ", response.success);
                        console.log("user after: " + userservice.user);
                        vm.user = userservice.user;
                        vm.email = "";
                        vm.passwd = "";
                        vm.dropdownMessage = vm.user.email;
                        vm.dropdownIsOpen = false;
                    })
                .catch(
                    function (response) { // HTTP Failure
                        console.log("HTTP failure");
                        console.log("user after: " + userservice.user);
                    });
        }

        function register() {
            userservice.register(vm.email, vm.passwd)
                .then(
                    function (response) { // HTTP Success
                        console.log("HTTP suucess");
                        console.log("Register success: ", response.success);
                    })
                .catch(
                    function (response) { // HTTP Failure
                        console.log("HTTP failure");
                        console.log("user after: " + userservice.user);
                    });
        }

        function logout() {
            userservice.user = null;
            vm.user = null;
            vm.dropdownMessage = "Login";
            vm.dropdownIsOpen = false;
        }

    }
})();