(function () {
    'use strict';

    angular
        .module('app')
        .controller('ResetPasswordController', ResetPasswordController);

    ResetPasswordController.inject = ['$location', 'modalservice', 'userservice'];
    function ResetPasswordController($location, modalservice, userservice) {
        var vm = this;
        var key = $location.search().resetkey;
        vm.resetPasswordReset = resetPasswordReset;

        if (key != null) {
            vm.resetPasswordReset();
        }

        ////////////////

        function resetPasswordReset() {
            var modal = modalservice.openResetPasswordModal();

            modal.result
                .then(function (response) {
                    userservice.resetPasswordReset(key, response.password)
                        .then(function (response) {
                            if (response.success) {
                                modalservice.openGeneralModal('Success', 'You can now login with your new password.');
                                window.history.replaceState(null, null, window.location.pathname);
                            }
                            else {
                                var generalModal = modalservice.openGeneralModal('Error', 'Please try again.');

                                generalModal.result
                                    .then(function (response) {
                                        vm.resetPasswordReset();
                                    });
                            }
                        })
                        .catch(function (response) {
                            var generalModal = modalservice.openGeneralModal('Server Error', 'Please try again. If the issue persists, please try again later');

                            generalModal.result
                                .then(function (response) {
                                    vm.resetPasswordReset();
                                });
                        })
                })
                .catch(function (response) {
                    var confirmModal = modalservice.openConfirmModal('Are you sure?', 'Hit Cancel to reset your password or OK to leave.');

                    confirmModal.result
                        .then(function (response) {
                            window.history.replaceState(null, null, window.location.pathname);
                        })
                        .catch(function (response) {
                            vm.resetPasswordReset();
                        })
                })
        }
    }
})();