//angular.module('myApp', ['ngTagsInput']);
function submitMiscController($scope, $http) {
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
    }
