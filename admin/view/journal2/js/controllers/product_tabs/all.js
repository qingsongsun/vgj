define(['./../module', 'underscore'], function (module, _) {

    module.controller('ProductTabsAllController', ['$scope', '$location', '$routeParams', 'Rest', 'Spinner', function($scope, $location, $routeParams, Rest, Spinner){

        $scope.paginationTotalItems = 0;
        $scope.paginationCurrentPage = 1;

        $scope.filterModules = function (modules, page) {
            return modules.slice((page - 1) * 10, page * 10);
        };

        /* opened modules */
        $scope.module_id = $routeParams.module_id || null;
        $scope.opened_modules = [];

        /* scope vars */
        $scope.module_type = 'product_tabs';
        $scope.modules = [];
        $scope.layouts = _.clone(Journal2Config.layouts);

        /* get data */
        Rest.all({
            modules         : Rest.getModules($scope.module_type)
        }, function (response) {
            $scope.paginationTotalItems = response.modules.length;
            $scope.modules = response.modules;
            Spinner.hide();
        }, function (error) {
            Spinner.hide();
            /* @todo handle error */
            console.log(error);
        });

        /* add module */
        $scope.addModule = function (module, $event) {
            module.module_placements.push({
                module_id: module.module_id,
                layout_id: '',
                position: '',
                status: 1,
                sort_order: ''
            });
            $scope.opened_modules[module.module_id] = true;
        };

        /* remove module */
        $scope.removeModule = function ($index, module) {
            module.module_placements.splice($index, 1);
        };

    }]);

});