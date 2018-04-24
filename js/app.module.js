(function () {
    'use strict';

    angular.module('app', [
        'app.core',
        'app.users',
        'app.messages'
    ]).config(function ($locationProvider) {
        $locationProvider.html5Mode(true);
    });

})();