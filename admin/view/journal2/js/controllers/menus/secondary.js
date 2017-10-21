define(['./../module', 'underscore'], function (module, _) {

    module.factory('SecondaryMenuFactory', [function () {
        return {
            item: function () {
                return {
                    icon: {
                    },
                    mobile_view: 'icon',
                    menu: {
                        menu_type: 'opencart',
                        menu_item: {
                            page: 'common/home'
                        }
                    },
                    name_overwrite: '0',
                    target: '0',
                    enable_on_phone: '1',
                    enable_on_tablet: '1',
                    enable_on_desktop: '1'
                };
            },
            default_data: function () {
                return [
                    {
                        icon: {},
                        menu: {
                            menu_type: 'opencart',
                            menu_item: {
                                page: 'login'
                            }
                        },
                        name_overwrite: '0',
                        target: '0'
                    },
                    {
                        icon: {},
                        menu: {
                            menu_type: 'opencart',
                            menu_item: {
                                page: 'register'
                            }
                        },
                        name_overwrite: '0',
                        target: '0'
                    }
                ];
            }
        };
    }]);

    module.controller('SecondaryMenuController', function ($scope, $routeParams, $timeout, Spinner, Rest, SecondaryMenuFactory, MenuItemName) {

        $scope.store_id = $routeParams.store_id || Journal2Config.stores[0].store_id;
        $scope.items = [];
        $scope.close_others = false;

        $scope.isLoading = true;
        $timeout(function () {
            Rest.getSetting('secondary_menu', $scope.store_id).then(function (response) {
                if (response) {
                    $scope.items = response.items || [];
                    $scope.close_others = response.close_others;
                }
                $scope.items = _.map($scope.items, function (item) {
                    return _.extend(new SecondaryMenuFactory.item(), item);
                });
                $timeout(function () {
                    $scope.isLoading = false;
                    Spinner.hide();
                }, 1);

            }, function (error) {
                Spinner.hide();
                alert(error);
            });
        }, 500);

        $scope.addItem = function () {
            $scope.items.push(new SecondaryMenuFactory.item());
        };

        $scope.removeItem = function ($index) {
            $scope.items.splice($index, 1);
        };

        $scope.save = function ($event) {
            var $src = $($event.target || $event.srcElement);
            Spinner.show($src);
            Rest.setSetting('secondary_menu', $scope.store_id, { items: $scope.items, close_others: $scope.close_others }).then(function (response) {
                Spinner.hide($src);
            }, function (error) {
                Spinner.hide($src);
                alert(error);
            });
        };

        $scope.reset = function () {
            $scope.items = SecondaryMenuFactory.default_data();
            $scope.items = _.map($scope.items, function (item) {
                return _.extend(new SecondaryMenuFactory.item(), item);
            });
        };

        $scope.toggleAccordion = function (items, value) {
            _.each(items, function (item) {
                item.is_open = value;
            });
            if (value) {
                $scope.close_others = false;
            }
        };

        $scope.getItemName = MenuItemName;

    });

});