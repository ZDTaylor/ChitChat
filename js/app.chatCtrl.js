'use strict';

// Retrieve app module
var myApp = angular.module('myApp');

myApp.controller('ChatCtrl', ['$scope', function ($scope) {
    $scope.bodyMessage = "This is the main body of the application where the chats will be displayed.";
}]);