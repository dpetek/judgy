angular.module(
    'myApp.controllers',
    []
)

// MISC PROBLEM RATING CONTROLLER
.controller('problemRatingController', function ($scope, $http) {
    $scope.rate = 5;
    $scope.max = 5;
    $scope.didHover = false;
    $scope.myRating = 7.9;

    $scope.$watch('rate', function(value) {
        if ($scope.didHover) {
            $http({
                'url': '/api/rating.json',
                'method': "POST",
                'headers': {'Content-Type': 'application/x-www-form-urlencoded'},
                'data': $.param({
                    'target': $scope.targetId,
                    'targetType': $scope.targetType,
                    'value': value
                })
            }).success(function(data){
                $scope.rateMessage = "Your rating: ";
                $scope.starsColor = 'green';
                $scope.rate = data.rating;
            });
        }

    });

    $scope.hoveringOver = function(value) {
        $scope.didHover = true;
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
    $scope.registrationError = false;
    $scope.register = function() {
        $http({
            url: '/api/user.json',
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            data: $.param($scope.user)
        }).success(function(data) {
            window.location.reload();
            $scope.registrationError = false;
        }).error(function(data) {
            $scope.registrationError = data.message;
        });
    };
})

.controller('submitMiscController', function($scope, $http) {
    $scope.tags = [];
    $scope.loadTags = function(query) {
        return $http.get('/api/tag/lookup.json?query=' + query);
    };

    $scope.submit = function() {
        var url = '/api/problems/misc/submit.json';
        if ($scope.problem && $scope.problem.id && $scope.problem.id.length === 24) {
            url = '/api/problems/misc/' + $scope.problem.id + '/submit.json';
        }

        $http({
            url: url,
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
    $scope.submissionError = false;
    $scope.errorMessage = '';
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
                $scope.submissionError = false;
                if (data.solved === true) {
                    $scope.correctSolution = true;
                } else {
                    $scope.correctSolution = false;
                }
                $scope.submitDone = true;
            }).error(function(data) {
                $scope.errorMessage = data.message;
                $scope.submissionError = true;
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
                        $scope.successfulSubmission = true;
                        //do something
                    }).error(function(data) {
                        $scope.successfulSubmission = false;
                        $scope.submissionError = data.message;
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
            $scope.submitDone = false;
            $scope.submissionError = false;

            $scope.languages = [
                {langId: 'c', langName: 'C'},
                {langId: 'cpp', langName: 'C++'},
                {langId: 'py2', langName: 'Python2.7'},
                {langId: 'go', langName: 'Go'},
                {langId: 'java', langName: 'Java'},
                {langId: 'php', langName: 'PHP'}
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
                        $scope.submissionError = false;
                        $scope.submitDone = true;
                    }).error(function(data) {
                        $scope.submitDone = true;
                        $scope.submissionError = true;
                });
            };

            $scope.onFileSelect = function ($files) {
                $scope.uploadProgress = 0;
                $scope.selectedFile = $files;
            };
        }
    ])
    .controller('alertsController', function($scope, $http) {
        $scope.alertsCount = 3;
        $scope.alertsView = false;

        $scope.markViewed = function() {
            $http.post(
                '/api/notifications/markViewed.json',
                {}
            ).success(function(data){
                    $scope.alertsView = true;
                    $scope.alertsCount = 0;
                });
            $scope.alertsView = true;
        };
    })
    .controller('problemReviewController', function($scope, $http){
        $scope.approveProblem = function() {
            $http({
                url: '/api/problems/misc/' + $scope.problemId + '/approve.json',
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param(
                    {
                    }
                )
            }).success(function(data) {
            }).error(function(data) {
            });
        }
    })
    .controller('tutorialSubmitController', function($scope, $http) {
        $scope.submitTutorial = function () {
            $http({
                url: '/api/tutorial/submit.json',
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param($scope.tutorial)
            }).success(function(data) {
                    $scope.successfulSubmission = true;
                }).error(function(data, status, headers, config) {
                    $scope.submissionError =  data.message;
                });
        };
    })
    .controller('problemsListController', function($scope, $http) {
        $scope.problemVisible = {};
        $scope.deleteProblem = function(id, type) {
            $http(
                {
                    url: '/api/problems/' + type + '/' + id + '/delete.json',
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data: $.param({})
                }
            ).success(function (data) {
                $scope.problemVisible[id] = false;
            });
        }
    })
;