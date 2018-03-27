'use strict';

// Retrieve app module
var myApp = angular.module('myApp');

myApp.controller('NavCtrl', ['$scope', function ($scope) {
    $scope.headerMessage = "This is the navbar. logo, title, and signin button/username and dropdown go here.";
}]);