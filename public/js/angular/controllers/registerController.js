function registerController($scope, $http) {
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
}