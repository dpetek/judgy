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
    .directive('progressBar', [
        function () {
            return {
                link: function ($scope, el, attrs) {
                    $scope.$watch(attrs.progressBar, function (newValue) {
                        el.css('width', newValue.toString() + '%');
                    });
                }
            };
        }
    ]);
;