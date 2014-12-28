function logoutController($scope, $http) {
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
}