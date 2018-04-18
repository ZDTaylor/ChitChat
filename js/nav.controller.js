(function () {
    'use strict';

    angular
        .module('app')
        .controller('NavController', NavController);

    NavController.inject = ['userservice', 'modalservice'];
    function NavController(userservice, modalservice) {
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
        vm.resetPasswordEmail = resetPasswordEmail;

        vm.login(true);
        ////////////////

        function register() {
            userservice.register(vm.email, vm.passwd)
                .then(
                    function (response) { // HTTP Success
                        if (response.success === true) {
                            modalservice.openGeneralModal('Success', 'Please log in now.');
                        }
                        else {
                            modalservice.openGeneralModal('Error', 'Please try again.');
                        }
                    })
                .catch(
                    function (response) { // HTTP Failure
                        modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                    });
        }

        function login(suppress) {
            userservice.login(vm.email, vm.passwd)
                .then(
                    function (response) { // HTTP Success
                        if (response.success === true) {
                            vm.user = userservice.user;
                            vm.email = "";
                            vm.passwd = "";
                            vm.dropdownMessage = vm.user.email;
                            vm.dropdownIsOpen = false;
                        }
                        else {
                            if (!suppress) { modalservice.openGeneralModal('Error', 'Please try again.'); }
                        }
                    })
                .catch(
                    function (response) { // HTTP Failure
                        if (!suppress) { modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later'); }
                    });
        }

        function logout() {
            userservice.logout()
                .then(
                    function (response) {
                        if (response.success === true) {
                            vm.user = userservice.user;
                            vm.dropdownMessage = "Login";
                            vm.dropdownIsOpen = false;
                        }
                        else {
                            modalservice.openGeneralModal('Error', 'Please try again.');
                        }
                    })
                .catch(
                    function (response) {
                        modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                    });
        }

        function deleteAccount() {
            var modal = modalservice.openConfirmModal('Are you sure?', 'This action will permanently delete your account!');

            // Only delete if user confirms the action
            modal.result.then(function () {
                userservice.deleteAccount()
                    .then(
                        function (response) {
                            if (response.success === true) {
                                vm.user = userservice.user;
                                vm.dropdownMessage = "Login";
                                vm.dropdownIsOpen = false;
                            }
                            else {
                                modalservice.openGeneralModal('Error', 'Please try again.');
                            }
                        })
                    .catch(
                        function (response) {
                            modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                        });
            });
        }

        function resetPasswordEmail() {
            userservice.resetPasswordEmail(vm.email)
                .then(
                    function (response) {
                        if (response.success) {
                            vm.email = "";
                            modalservice.openGeneralModal('Success', 'Please check your email for instructions on resetting your password. (Allow up to 30 minutes).');
                        }
                        else {
                            modalservice.openGeneralModal('Error', 'Please try again.');
                        }
                    })
                .catch(
                    function (response) {
                        modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');
                    });
        }

    }
})();