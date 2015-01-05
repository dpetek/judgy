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
            url: '/api/problems/misc/submit.json',
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
            url: '/api/problems/misc/' + $scope.id + '/answer.json',
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

.controller('submitAlgorithmProblemController', [
        '$scope',
        '$upload',
        function ($scope, $upload) {
            $scope.problem = {};
            $scope.selectedFile = [];
            $scope.uploadProgress = 0;

            $scope.submitProblem = function () {
                var file = $scope.selectedFile[0];
                $scope.upload = $upload.upload({
                    url: '/api/problems/algorithm/submit.json',
                    method: 'POST',
                    data: $scope.problem,
                    file: file
                }).progress(function (evt) {
                        $scope.uploadProgress = parseInt(100.0 * evt.loaded / evt.total, 10);
                    }).success(function (data) {
                        //do something
                    });
            };

            $scope.onFileSelect = function ($files) {
                $scope.uploadProgress = 0;
                $scope.selectedFile = $files;
            };
        }
    ])
    .controller('submitAlgorithmSolutionController', [
        '$scope',
        '$upload',
        function($scope, $upload) {
            $scope.problem = {};
            $scope.selectedFile = [];
            $scope.uploadProgress = 0;

            $scope.languages = [
                {langId: 'c', langName: 'C'},
                {langId: 'cpp', langName: 'C++'},
                {langId: 'py2', langName: 'Python2.7'},
                {langId: 'py3', langName: 'Python3'},
                {langId: 'go', langName: 'Go'},
                {langId: 'java', langName: 'Java'}
            ];

            $scope.submitSolution = function () {
                var file = $scope.selectedFile[0];
                $scope.upload = $upload.upload({
                    url: '/api/problems/algorithm/' + $scope.id + '/answer.json',
                    method: 'POST',
                    data: $scope.solution,
                    file: file
                }).progress(function (evt) {
                        $scope.uploadProgress = parseInt(100.0 * evt.loaded / evt.total, 10);
                    }).success(function (data) {
                        //do something
                    });
            };

            $scope.onFileSelect = function ($files) {
                $scope.uploadProgress = 0;
                $scope.selectedFile = $files;
            };
        }
    ])
;