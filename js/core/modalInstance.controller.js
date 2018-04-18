(function () {
    'use strict';

    angular
        .module('app.core')
        .controller('ModalInstanceController', ModalInstanceController);

    ModalInstanceController.inject = ['$uibModalInstance', 'modalParams'];
    function ModalInstanceController($uibModalInstance, modalParams) {
        var vm = this;
        vm.modalParams = modalParams;
        vm.response = {};
        vm.ok = ok;
        vm.cancel = cancel;

        ////////////////

        function ok() {
            $uibModalInstance.close(vm.response);
        }

        function cancel() {
            $uibModalInstance.dismiss(vm.response);
        }
    }
})();