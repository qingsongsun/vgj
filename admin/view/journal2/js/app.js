define([
    'spin',
    'angular',
    'underscore',
    'angular-strap',
    'angular-route',
    'angular-ls',
    'angular-bootstrap',
    'angular-select2',
    'angular-table',
    'simple-slider',
    'controllers/index',
    'directives/index',
    'services/index'
], function (Spinner, ng, _) {
    'use strict';

    var app = ng.module('journal', [
        'ngRoute',
        'ngTable',
        '$strap.directives',
        'ui.bootstrap',
        'ui.select2',
        'LocalStorageModule',
        'controllers',
        'directives',
        'services'
    ]);

    app.controller('MainController', function ($scope, SkinManager) {
        $scope.getActiveSkin = SkinManager.getActiveSkin;
    });

    app.factory('SkinManager', function (Rest, $q) {
        var skins_data = null;
        var active_skin = null;

        return {
            getActiveSkin: function () {
                return active_skin || Journal2Config.active_skin;
            },
            setActiveSkin: function (skin) {
                active_skin = skin;
            },
            getSkins: function () {
                var deferred = $q.defer();

                if (!skins_data) {
                    Rest.getSkins().then(function (response) {
                        deferred.resolve(response);
                    }, function (error) {
                        deferred.reject(error);
                    });
                } else {
                    deferred.resolve(skins_data.skins);
                }

                return deferred.promise;
            },
            addSkin: function (name) {
                skins_data.skins.push({
                    name: name,
                    id: ++skins_data.last_id
                });
                Rest.setSetting('skins_data', -1, skins_data).then(function (response) {
                }, function (error) {
                    alert(error);
                });
            },
            deleteSkin: function ($index) {
                skins_data.skins.splice($index, 1);
                Rest.setSetting('skins_data', -1, skins_data).then(function (response) {
                }, function (error) {
                    alert(error);
                });
            }
        };
    });

    app.factory('History', function (localStorageService) {
        var history = localStorageService.get('j2history') || [];
        return {
            add: function (url) {
                if (url === '/home' || url === '/' || url === '') {
                    return;
                }
                history = _.filter(history, function (item) {
                    return item !== url;
                });
                history.unshift(url);
                history = _.first(history, 25);
                localStorageService.set('j2history', history);
            },
            get: function () {
                return history;
            }
        };
    });

    app.config(['$routeProvider', function ($routeProvider) {
        $routeProvider
            .when('/', {
                redirectTo: '/home'
            })
            .when('/home', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=home&token=' + Journal2Config.token,
                controller: 'HomeController'
            })
            /* settings */
            .when('/settings/general/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/general&token=' + Journal2Config.token,
                controller: 'GeneralSettingsController'
            })
            .when('/settings/productlabels/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/productlabels&token=' + Journal2Config.token,
                controller: 'ProductLabelsSettingsController'
            })
            .when('/settings/notification/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/notification&token=' + Journal2Config.token,
                controller: 'NotificationSettingsController'
            })
            .when('/settings/countdown/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/countdown&token=' + Journal2Config.token,
                controller: 'CountdownSettingsController'
            })
            .when('/settings/quickview/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/quickview&token=' + Journal2Config.token,
                controller: 'QuickviewSettingsController'
            })
            .when('/settings/blog/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/blog&token=' + Journal2Config.token,
                controller: 'BlogSettingsController'
            })
            .when('/settings/blogmodules/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/blogmodules&token=' + Journal2Config.token,
                controller: 'BlogModulesSettingsController'
            })
            .when('/settings/blogpostpage/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/blogpostpage&token=' + Journal2Config.token,
                controller: 'BlogPostPageSettingsController'
            })
            .when('/settings/bloglanguage/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/bloglanguage&token=' + Journal2Config.token,
                controller: 'BlogLanguageSettingsController'
            })
            .when('/settings/header/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/header&token=' + Journal2Config.token,
                controller: 'HeaderSettingsController'
            })
            .when('/settings/headermenus/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/headermenus&token=' + Journal2Config.token,
                controller: 'HeaderMenusSettingsController'
            })
            .when('/settings/modulecarousel/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/modulecarousel&token=' + Journal2Config.token,
                controller: 'ModuleCarouselSettingsController'
            })
            .when('/settings/modulecmsblocks/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/modulecmsblocks&token=' + Journal2Config.token,
                controller: 'ModuleCMSBlocksSettingsController'
            })
            .when('/settings/modulecustomsections/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/modulecustomsections&token=' + Journal2Config.token,
                controller: 'ModuleCustomSectionsSettingsController'
            })
            .when('/settings/moduleheadlinerotator/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/moduleheadlinerotator&token=' + Journal2Config.token,
                controller: 'ModuleHeadlineRotatorSettingsController'
            })
            .when('/settings/modulenewsletter/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/modulenewsletter&token=' + Journal2Config.token,
                controller: 'ModuleNewsletterSettingsController'
            })
            .when('/settings/moduletextrotator/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/moduletextrotator&token=' + Journal2Config.token,
                controller: 'ModuleTextRotatorSettingsController'
            })
            .when('/settings/modulephotogallery/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/modulephotogallery&token=' + Journal2Config.token,
                controller: 'ModulePhotoGallerySettingsController'
            })
            .when('/settings/modulepopup/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/modulepopup&token=' + Journal2Config.token,
                controller: 'ModulePopupSettingsController'
            })
            .when('/settings/moduleflyout/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/moduleflyout&token=' + Journal2Config.token,
                controller: 'ModuleFlyoutSettingsController'
            })
            .when('/settings/moduleslider/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/moduleslider&token=' + Journal2Config.token,
                controller: 'ModuleSliderSettingsController'
            })
            .when('/settings/modulesuperfilter/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/modulesuperfilter&token=' + Journal2Config.token,
                controller: 'ModuleSuperFilterSettingsController'
            })
            .when('/settings/welcome/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/welcome&token=' + Journal2Config.token,
                controller: 'WelcomeSettingsController'
            })
            .when('/settings/modules/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/modules&token=' + Journal2Config.token,
                controller: 'ModulesSettingsController'
            })
            .when('/settings/modules/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/modules&token=' + Journal2Config.token,
                controller: 'ModulesSettingsController'
            })


            .when('/settings/pages/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/pages&token=' + Journal2Config.token,
                controller: 'PagesSettingsController'
            })
            .when('/settings/journalcheckout/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/journalcheckout&token=' + Journal2Config.token,
                controller: 'JournalCheckoutSettingsController'
            })
            .when('/settings/productpage/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/productpage&token=' + Journal2Config.token,
                controller: 'ProductPageSettingsController'
            })

            .when('/settings/category/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/category&token=' + Journal2Config.token,
                controller: 'CategorySettingsController'
            })
            .when('/settings/productlist/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/productlist&token=' + Journal2Config.token,
                controller: 'ProductListSettingsController'
            })
            .when('/settings/productgrid/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/productgrid&token=' + Journal2Config.token,
                controller: 'ProductGridSettingsController'
            })
            .when('/settings/footer/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/footer&token=' + Journal2Config.token,
                controller: 'FooterSettingsController'
            })
            .when('/settings/catalog/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/catalog&token=' + Journal2Config.token,
                controller: 'CatalogSettingsController'
            })
            .when('/settings/custom_code/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/custom_code&token=' + Journal2Config.token,
                controller: 'CustomCodeSettingsController'
            })

            .when('/settings/sidecolumn/:skin_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/sidecolumn&token=' + Journal2Config.token,
                controller: 'SideColumnSettingsController'
            })
            .when('/settings2/:category', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings&token=' + Journal2Config.token,
                controller: 'SettingsController'
            })
            /* system */
            .when('/settings/system', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/system&token=' + Journal2Config.token,
                controller: 'SystemSettingsController'
            })
            /* import_export */
            .when('/settings/import_export', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=settings/import_export&token=' + Journal2Config.token,
                controller: 'ImportExportSettingsController'
            })
            /* menus */
            .when('/menus/primary/:store_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=menus/primary&token=' + Journal2Config.token,
                controller: 'PrimaryMenuController'
            })
            .when('/menus/secondary/:store_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=menus/secondary&token=' + Journal2Config.token,
                controller: 'SecondaryMenuController'
            })
            .when('/menus/main/:store_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=menus/main&token=' + Journal2Config.token,
                controller: 'MainMenuController'
            })
            /* footer */
            .when('/footer/menu/:store_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=footer/menu&token=' + Journal2Config.token,
                controller: 'FooterMenuController'
            })
            .when('/footer/copyright/:store_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=footer/copyright&token=' + Journal2Config.token,
                controller: 'FooterCopyrightController'
            })
            .when('/footer/payments/:store_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=footer/payments&token=' + Journal2Config.token,
                controller: 'FooterPaymentsController'
            })
            /* blog */
            .when('/blog/settings/:store_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog/settings&token=' + Journal2Config.token,
                controller: 'GeneralBlogSettingsController'
            })
            .when('/blog/categories', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog/categories/all&token=' + Journal2Config.token,
                controller: 'BlogCategoriesAllController'
            })
            .when('/blog/categories/form/:category_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog/categories/form&token=' + Journal2Config.token,
                controller: 'BlogCategoriesFormController'
            })
            .when('/blog/posts', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog/posts/all&token=' + Journal2Config.token,
                controller: 'BlogPostsAllController'
            })
            .when('/blog/posts/form/:post_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog/posts/form&token=' + Journal2Config.token,
                controller: 'BlogPostsFormController'
            })
            .when('/blog/comments', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog/comments/all&token=' + Journal2Config.token,
                controller: 'BlogCommentsAllController'
            })
            .when('/blog/comments/form/:comment_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog/comments/form&token=' + Journal2Config.token,
                controller: 'BlogCommentsFormController'
            })
            /* blog modules */
            .when('/module/blog_categories/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/categories/all&token=' + Journal2Config.token,
                controller: 'BlogModuleCategoriesAllController'
            })
            .when('/module/blog_categories/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/categories/form&token=' + Journal2Config.token,
                controller: 'BlogModuleCategoriesFormController'
            })
            .when('/module/blog_comments/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/comments/all&token=' + Journal2Config.token,
                controller: 'BlogModuleCommentsAllController'
            })
            .when('/module/blog_comments/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/comments/form&token=' + Journal2Config.token,
                controller: 'BlogModuleCommentsFormController'
            })
            .when('/module/blog_search/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/search/all&token=' + Journal2Config.token,
                controller: 'BlogModuleSearchAllController'
            })
            .when('/module/blog_search/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/search/form&token=' + Journal2Config.token,
                controller: 'BlogModuleSearchFormController'
            })
            .when('/module/blog_side_posts/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/side_posts/all&token=' + Journal2Config.token,
                controller: 'BlogModuleSidePostsAllController'
            })
            .when('/module/blog_side_posts/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/side_posts/form&token=' + Journal2Config.token,
                controller: 'BlogModuleSidePostsFormController'
            })
            .when('/module/blog_posts/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/posts/all&token=' + Journal2Config.token,
                controller: 'BlogModulePostsAllController'
            })
            .when('/module/blog_posts/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/posts/form&token=' + Journal2Config.token,
                controller: 'BlogModulePostsFormController'
            })
            .when('/module/blog_tags/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/tags/all&token=' + Journal2Config.token,
                controller: 'BlogModuleTagsAllController'
            })
            .when('/module/blog_tags/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=blog_modules/tags/form&token=' + Journal2Config.token,
                controller: 'BlogModuleTagsFormController'
            })
            /* custom blocks */
            .when('/module/cms_blocks/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=cms_blocks/all&token=' + Journal2Config.token,
                controller: 'CMSBlocksAllController'
            })
            .when('/module/cms_blocks/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=cms_blocks/form&token=' + Journal2Config.token,
                controller: 'CMSBlocksFormController'
            })
            /* side blocks */
            .when('/module/side_blocks/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=side_blocks/all&token=' + Journal2Config.token,
                controller: 'SideBlocksAllController'
            })
            .when('/module/side_blocks/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=side_blocks/form&token=' + Journal2Config.token,
                controller: 'SideBlocksFormController'
            })
            /* slider */
            .when('/module/slider/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=slider/all&token=' + Journal2Config.token,
                controller: 'SliderAllController'
            })
            .when('/module/slider/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=slider/form&token=' + Journal2Config.token,
                controller: 'SliderFormController'
            })
            /* simple slider */
            .when('/module/simple_slider/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=simple_slider/all&token=' + Journal2Config.token,
                controller: 'SimpleSliderAllController'
            })
            .when('/module/simple_slider/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=simple_slider/form&token=' + Journal2Config.token,
                controller: 'SimpleSliderFormController'
            })
            /* fullscreen slider */
            .when('/module/fullscreen_slider/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=fullscreen_slider/all&token=' + Journal2Config.token,
                controller: 'FullScreenSliderAllController'
            })
            .when('/module/fullscreen_slider/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=fullscreen_slider/form&token=' + Journal2Config.token,
                controller: 'FullScreenSliderFormController'
            })
            /* photo gallery */
            .when('/module/photo_gallery/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=photo_gallery/all&token=' + Journal2Config.token,
                controller: 'PhotoGalleryAllController'
            })
            .when('/module/photo_gallery/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=photo_gallery/form&token=' + Journal2Config.token,
                controller: 'PhotoGalleryFormController'
            })
            /* text rotator */
            .when('/module/text_rotator/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=text_rotator/all&token=' + Journal2Config.token,
                controller: 'TextRotatorAllController'
            })
            .when('/module/text_rotator/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=text_rotator/form&token=' + Journal2Config.token,
                controller: 'TextRotatorFormController'
            })
            /* headline rotator */
            .when('/module/headline_rotator/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=headline_rotator/all&token=' + Journal2Config.token,
                controller: 'HeadlineRotatorAllController'
            })
            .when('/module/headline_rotator/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=headline_rotator/form&token=' + Journal2Config.token,
                controller: 'HeadlineRotatorFormController'
            })
            /* header_notice */
            .when('/module/header_notice/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=header_notice/all&token=' + Journal2Config.token,
                controller: 'HeaderNoticeAllController'
            })
            .when('/module/header_notice/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=header_notice/form&token=' + Journal2Config.token,
                controller: 'HeaderNoticeFormController'
            })
            /* product tabs */
            .when('/module/product_tabs/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=product_tabs/all&token=' + Journal2Config.token,
                controller: 'ProductTabsAllController'
            })
            .when('/module/product_tabs/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=product_tabs/form&token=' + Journal2Config.token,
                controller: 'ProductTabsFormController'
            })
            /* multi module */
            .when('/module/advanced_grid/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=advanced_grid/all&token=' + Journal2Config.token,
                controller: 'AdvancedGridAllController'
            })
            .when('/module/advanced_grid/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=advanced_grid/form&token=' + Journal2Config.token,
                controller: 'AdvancedGridFormController'
            })
            /* carousel grid */
            .when('/module/carousel_grid/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=carousel_grid/all&token=' + Journal2Config.token,
                controller: 'CarouselGridAllController'
            })
            .when('/module/carousel_grid/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=carousel_grid/form&token=' + Journal2Config.token,
                controller: 'CarouselGridFormController'
            })
            /* side column menu */
            .when('/module/side_column_menu/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=side_column_menu/all&token=' + Journal2Config.token,
                controller: 'SideColumnMenuAllController'
            })
            .when('/module/side_column_menu/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=side_column_menu/form&token=' + Journal2Config.token,
                controller: 'SideColumnMenuFormController'
            })
            /* side category */
            .when('/module/side_category/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=side_category/all&token=' + Journal2Config.token,
                controller: 'SideCategoryAllController'
            })
            .when('/module/side_category/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=side_category/form&token=' + Journal2Config.token,
                controller: 'SideCategoryFormController'
            })
            /* custom sections */
            .when('/module/custom_sections/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=custom_sections/all&token=' + Journal2Config.token,
                controller: 'CustomSectionsAllController'
            })
            .when('/module/custom_sections/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=custom_sections/form&token=' + Journal2Config.token,
                controller: 'CustomSectionsFormController'
            })
            /* carousel */
            .when('/module/carousel/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=carousel/all&token=' + Journal2Config.token,
                controller: 'CarouselAllController'
            })
            .when('/module/carousel/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=carousel/form&token=' + Journal2Config.token,
                controller: 'CarouselFormController'
            })
            /* static banners */
            .when('/module/static_banners/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=static_banners/all&token=' + Journal2Config.token,
                controller: 'StaticBannersAllController'
            })
            .when('/module/static_banners/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=static_banners/form&token=' + Journal2Config.token,
                controller: 'StaticBannersFormController'
            })
            /* slider */
            .when('/module/layer_slider/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=layer_slider/all&token=' + Journal2Config.token,
                controller: 'LayerSliderAllController'
            })
            .when('/module/layer_slider/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=layer_slider/form&token=' + Journal2Config.token,
                controller: 'LayerSliderFormController'
            })
            /* super filter */
            .when('/module/super_filter/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=super_filter/all&token=' + Journal2Config.token,
                controller: 'SuperFilterAllController'
            })
            .when('/module/super_filter/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=super_filter/form&token=' + Journal2Config.token,
                controller: 'SuperFilterFormController'
            })
            /* side products */
            .when('/module/side_products/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=side_products/all&token=' + Journal2Config.token,
                controller: 'SideProductsAllController'
            })
            .when('/module/side_products/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=side_products/form&token=' + Journal2Config.token,
                controller: 'SideProductsFormController'
            })
            /* newsletter */
            .when('/module/newsletter/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=newsletter/all&token=' + Journal2Config.token,
                controller: 'NewsletterAllController'
            })
            .when('/module/newsletter/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=newsletter/form&token=' + Journal2Config.token,
                controller: 'NewsletterFormController'
            })
            /* popup */
            .when('/module/popup/all/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=popup/all&token=' + Journal2Config.token,
                controller: 'PopupAllController'
            })
            .when('/module/popup/form/:module_id?', {
                templateUrl: 'index.php?route=module/journal2/tpl&tpl=popup/form&token=' + Journal2Config.token,
                controller: 'PopupFormController'
            });
    }]);

    app.run(['$rootScope', '$location', 'Spinner', 'History', function($rootScope, $location, Spinner, History){
        $(function () {
            $('#nav > li, #nav > li > ul > li').hover(function () {
                $('>ul', $(this)).removeClass('hide-menu').stop(true, true).fadeIn(0);
            }, function () {
                $('>ul', $(this)).removeClass('hide-menu').stop(true, true).fadeOut(0);
            });

            $('#nav li > ul').on('click', function () {
                $(this).addClass('hide-menu');
            });

        });

        $(window).scroll(function(){
            if ($(this).scrollTop() > 90) {
                $('.module-header').addClass('fixed');
            } else {
                $('.module-header').removeClass('fixed');
            }
        });

        /* tips */
        $('a.journal-tip').on('click', function () {
            var model = $(this).closest('li').find('[data-ng-model]').attr('data-ng-model').replace('settings.', '');
            window.open('http://docs.digital-atelier.com/opencart/journal/tips/' + model + '.jpg', '_blank');
            return false;
        });

        $rootScope.$on("$routeChangeStart", function() {
            History.add($location.path());
            Spinner.show();
            /* parse path */
            var path = $location.path().split('/');
            var s1 = path[1] || null;
            var s2 = path[2] || null;
            var s = '/' + s1 + '/' + s2;

            /* locate dom elements */
            var $a = $('a[href*="' + s + '"]');
            var $parent = $a.closest('#nav > li');
            var $ul = $('>ul', $parent);

            /* remove old state */
            $("#nav li a").not($a).removeClass("open");

            /* add new state */
            $a.addClass('open');
        });

    }]);

    return app;
});
