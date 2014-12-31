angular.module(
    'myApp.directives',
    []
)

.directive('problemStatement', [function() {
        return {
            restrict: 'E',
            replace: true,
            transclude: true,
            templateUrl: "/../templates/problem-statement.html"
        };
}])
;