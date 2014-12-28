function loginController($scope, $http) {
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
}