(function () {
    'use strict';

    angular
        .module('app.users')
        .constant('apiUrl', 'api/');

    angular
        .module('app.messages')
        .constant('apiUrl', 'api/');
})();