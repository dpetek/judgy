angular.module(
    'myApp.controllers',
    []
)

// MISC PROBLEM RATING CONTROLLER
.controller('miscProblemRatingController', function ($scope, $http) {
    $scope.rate = 5;
    $scope.max = 5;

    $scope.$watch('rate', function(value) {
        // todo api request to save rating
    });

    $scope.hoveringOver = function(value) {

    };
})

// LOGOUT CONTROLLER
.controller('logoutController', function ($scope, $http) {
    $scope.logout = function() {
        $http({
            url: '/api/user/logout.json',
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(
                {
                }
            )
        }).success(function(data) {
                window.location.reload();
            });
    };
})

// LOGIN CONTROLLER
.controller('loginController', function($scope, $http) {
    $scope.login = function() {
        $http({
            url: '/api/user/login.json',
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(
                {
                    username: $scope.username,
                    password: $scope.password
                }
            )
        }).success(function(data) {
                window.location.reload();
            });
    };
})

// REGISTER CONTROLLER
.controller('registerController', function($scope, $http) {
    $scope.register = function() {
        $http({
            url: '/api/user.json',
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param($scope.user)
        }).success(function(data) {
                console.log(data)
            });
    };
})

.controller('submitMiscController', function($scope, $http) {
    $scope.tags = [];
    $scope.loadTags = function(query) {
        return $http.get('/api/tag/lookup.json?query=' + query);
    };

    $scope.submit = function() {
        $http({
            url: '/api/misc.json',
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param($scope.problem)
        }).success(function(data) {
                $scope.successfulSubmission = true;
            }).error(function(data, status, headers, config) {
                $scope.submissionError =  data.message;
            });
    }
})

.controller('submitMiscSolutionController', function($scope, $http) {
    $scope.submit = function() {
        $http({
            url: '/api/misc/' + $scope.id + '/answer.json',
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param(
                {
                    answer: $scope.answer
                }
            )
        }).success(function(data) {
                if (data.solved === true) {
                    $scope.correctSolution = true;
                } else {
                    $scope.correctSolution = false;
                }
            });
    };
})

.controller('submitAlgorithmProblemController', function($scope) {
    $scope.submit = function () {
        var data = $scope.problem;
    }

});