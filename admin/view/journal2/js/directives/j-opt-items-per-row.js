define(['./module', 'underscore'], function (module, _) {

    module
        .directive('jOptItemsPerRow', ['Rest', '$timeout', function (Rest, $timeout) {
            return {
                replace: true,
                require: '?ngModel',
                scope: {
                    ngModel: '='
                },
                restrict: 'E',
                templateUrl: 'view/journal2/tpl/directives/j-opt-items-per-row.html?ver=' + Journal2Config.version,
                controller: ['$scope', '$modal', '$element', '$attrs', function ($scope, $modal, $element, $attrs) {
                    $scope.edit = function () {
                        $scope.ngModel = $scope.ngModel || {};
                        $scope.ngModel.value = $scope.ngModel.value || {};
                        if ($attrs.values) {
                            $scope.ngModel.values = $attrs.values;
                        }
                        if ($attrs.step) {
                            $scope.ngModel.step = $attrs.step;
                        }
                        if ($attrs.range) {
                            $scope.ngModel.range = $attrs.range;
                        }
                        _.each([
                            'mobile', 'mobile1',
                            'tablet', 'tablet1', 'tablet2',
                            'desktop', 'desktop1', 'desktop2',
                            'large_desktop', 'large_desktop1', 'large_desktop2'
                        ], function (type) {
                            $scope.ngModel.value[type] = $scope.ngModel.value[type] || {};
                            var opts = {
                                value: $scope.ngModel.value[type].value
                            };
                            if ($scope.ngModel.values) {
                                opts.values = $scope.ngModel.values;
                            }
                            if ($scope.ngModel.range) {
                                opts.range = $scope.ngModel.range;
                            }
                            if ($scope.ngModel.step) {
                                opts.step = $scope.ngModel.step;
                            }
                            $scope.ngModel.value[type] = opts;
                        });
                        $modal.open({
                            templateUrl: 'view/journal2/tpl/directives/j-opt-items-per-row-editor.html?ver=' + Journal2Config.version,
                            resolve: {
                                ngModel: function () { return $scope.ngModel; }
                            },
                            controller: function ($scope, $rootScope, $modalInstance, ngModel) {
                                ngModel = ngModel || {};
                                $scope.ngModel = ngModel.value || {};
                                $scope.hide_columns = ngModel.hide_columns;
                                $scope.hide_phone = ngModel.hide_phone;

                                $scope.save = function () {
                                    $modalInstance.close();
                                };
                            }
                        });
                    };
                }]
            };
        }]);

});