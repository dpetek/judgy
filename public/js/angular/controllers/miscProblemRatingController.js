angular.module('myApp', ['ui.bootstrap', 'ngTagsInput', 'textAngular']);
angular.module('myApp').controller('miscProblemRatingController', function ($scope, $http) {
    $scope.rate = 1;
    $scope.max = 5;

    $scope.$watch('rate', function(value) {
        // todo api request to save rating
    });

    $scope.hoveringOver = function(value) {

    };
});