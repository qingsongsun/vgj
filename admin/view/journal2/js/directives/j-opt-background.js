define(['./module', 'underscore'], function (module, _) {

    module
        .directive('jOptBackground', ['Rest', function () {
            return {
                replace: true,
                require: '?ngModel',
                scope: {
                    ngModel: '='
                },
                restrict: 'E',
                templateUrl: 'view/journal2/tpl/directives/j-opt-background.html?ver=' + Journal2Config.version,
                controller: ['$scope', '$attrs', '$modal', function ($scope, $attrs, $modal) {
                    $scope.ngModel = $scope.ngModel || {};
                    $scope.ngModel.value = $scope.ngModel.value || {};
                    $scope.edit = function () {
                        $modal.open({
                            templateUrl: 'view/journal2/tpl/directives/j-opt-background-editor.html?ver=' + Journal2Config.version,
                            resolve: {
                                ngModel: function () { return $scope.ngModel; },
                                bgcolor: function () { return $attrs.bgcolor; }
                            },
                            controller: function ($scope, $rootScope, $modalInstance, ngModel, bgcolor) {
                                $scope.ngModel = ngModel.value || {};
                                $scope.bgcolor = bgcolor;
                                $scope.save = function () {
                                    $modalInstance.close();
                                };
                            }
                        });
                    };
                    $scope.$watch('ngModel', function (val) {
                        val = val || {};
                        val.value = val.value || {};
                        if (Object.prototype.toString.call(val.value) === '[object Array]') {
                            val.value = {};
                        }
                        if (!val.value.bgimage_attach) {
                            val.value.bgimage_attach = 'scroll';
                        }
                        $scope.ngModel = val;
                    });
                }]
            };
        }]);

});