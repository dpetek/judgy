function submitMiscSolutionController($scope, $http) {
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
}