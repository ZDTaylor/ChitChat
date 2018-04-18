(function () {
    'use strict';

    angular
        .module('app.core')
        .factory('modalservice', modalservice);

    modalservice.inject = ['$uibModal'];
    function modalservice($uibModal) {
        var service = {
            openGeneralModal: openGeneralModal,
            openConfirmModal: openConfirmModal,
            openResetPasswordModal: openResetPasswordModal,
            openSuspendModal: openSuspendModal
        };

        return service;

        ////////////////
        function openGeneralModal(title, content) {
            return $uibModal.open({
                templateUrl: 'generalModal.html',
                controller: 'ModalInstanceController',
                controllerAs: 'modal',
                resolve: {
                    modalParams: function () {
                        return { title: title, content: content }
                    }
                }
            });
        }

        function openConfirmModal(title, content) {
            return $uibModal.open({
                templateUrl: 'confirmModal.html',
                backdrop: 'static',
                controller: 'ModalInstanceController',
                controllerAs: 'modal',
                resolve: {
                    modalParams: function () {
                        return { title: title, content: content }
                    }
                }
            });
        }

        function openResetPasswordModal() {
            return $uibModal.open({
                templateUrl: 'resetPasswordModal.html',
                controller: 'ModalInstanceController',
                controllerAs: 'modal',
                resolve: {
                    modalParams: function () {
                        return {}
                    }
                }
            });
        }

        function openSuspendModal() {
            return $uibModal.open({
                templateUrl: 'suspendModal.html',
                controller: 'ModalInstanceController',
                controllerAs: 'modal',
                resolve: {
                    modalParams: function () {
                        return {}
                    }
                }
            });
        }
    }
})();