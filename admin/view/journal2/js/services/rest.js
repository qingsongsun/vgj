define(['./module'], function(module){
    module.factory('Rest', ['Ajax', 'Url', '$q', function(Ajax, Url, $q){
        return {
            getLayouts: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/modules/layouts', 'token=' + Journal2Config.token));
            },
            getLanguages: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/modules/languages', 'token=' + Journal2Config.token));
            },
            getTopCategories: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/catalog/top_categories', 'token=' + Journal2Config.token));
            },
            getFeaturedModules: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/catalog/get_featured', 'token=' + Journal2Config.token));
            },
            getAllModules: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/modules/all', 'token=' + Journal2Config.token));
            },
            getMultiModules: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/modules/multi_modules', 'token=' + Journal2Config.token));
            },
            getModules: function(module_type) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/modules/all', 'module_type=' + module_type, 'token=' + Journal2Config.token));
            },
            getModule: function(module_id) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/modules/get', 'module_id=' + module_id, 'token=' + Journal2Config.token));
            },
            addModule: function(module_type, module_data) {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/modules/add', 'module_type=' + module_type, 'token=' + Journal2Config.token), {'module_data' : module_data});
            },
            editModule: function(module_id, module_data) {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/modules/edit', 'module_id=' + module_id, 'token=' + Journal2Config.token), {'module_data' : module_data});
            },
            deleteModule: function(module_id) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/modules/remove', 'module_id=' + module_id, 'token=' + Journal2Config.token));
            },
            getModulePlacement: function(module_type) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/modules/load', 'module_type=' + module_type, 'token=' + Journal2Config.token));
            },
            saveModulePlacement: function(module_type, module_data) {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/modules/save', 'module_type=' + module_type, 'token=' + Journal2Config.token), {'module_data' : module_data});
            },
            findProducts: function(filter_name) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/catalog/products', 'filter_name=' + filter_name, 'token=' + Journal2Config.token));
            },
            findCategories: function(filter_name) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/catalog/categories', 'filter_name=' + filter_name, 'token=' + Journal2Config.token));
            },
            findManufacturers: function(filter_name) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/catalog/manufacturers', 'filter_name=' + filter_name, 'token=' + Journal2Config.token));
            },
            findInformation: function(filter_name) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/catalog/information', 'filter_name=' + filter_name, 'token=' + Journal2Config.token));
            },
            getTransitions: function() {
                return Ajax.get(Url.generateLink('view/journal2/tpl/slider2/transitions.json'));
            },
            getFonts: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/fonts/get', 'token=' + Journal2Config.token));
            },
            getIcons: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/fonts/icons', 'token=' + Journal2Config.token));
            },
            loadSettings: function(category, theme_id) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/settings/load', 'token=' + Journal2Config.token, 'category=' + category, 'theme_id=' + theme_id));
            },
            loadDefaultSettings: function(category, theme_id) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/settings/load_default', 'token=' + Journal2Config.token, 'category=' + category, 'theme_id=' + theme_id));
            },
            getSiteWidth: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/settings/get_Site_width', 'token=' + Journal2Config.token));
            },
            saveSettings: function(settings, category, theme_id) {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/settings/save', 'token=' + Journal2Config.token, 'category=' + category, 'theme_id=' + theme_id), {settings: settings});
            },
            saveSettingsAs: function(name, settings, category, theme_id) {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/settings/save_as', 'token=' + Journal2Config.token, 'category=' + category, 'theme_id=' + theme_id), {settings: settings, name: name});
            },
            getSkins: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/settings/get_skins', 'token=' + Journal2Config.token));
            },
            deleteSkin: function(theme_id) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/settings/delete_skin', 'token=' + Journal2Config.token, 'theme_id=' + theme_id));
            },
            export: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/settings/export', 'token=' + Journal2Config.token));
            },
            getSetting: function(key, store_id) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/settings/get', 'key=' + key, 'store_id=' + store_id, 'token=' + Journal2Config.token));
            },
            setSetting: function(key, store_id, value) {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/settings/set', 'key=' + key, 'store_id=' + store_id, 'token=' + Journal2Config.token), {value : value});
            },
            getFilters: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/catalog/filters', 'token=' + Journal2Config.token));
            },
            clearCache: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/cache/clear_all', 'token=' + Journal2Config.token));
            },
            checkVersion: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/check_version', 'token=' + Journal2Config.token));
            },
            getImageOptimisationStatus: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/image_optimisation/status', 'token=' + Journal2Config.token));
            },
            getTableIndexesStatus: function() {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/optimisations/indexes', 'token=' + Journal2Config.token));
            },
            addTableIndexes: function() {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/optimisations/add_indexes', 'token=' + Journal2Config.token));
            },
            getSubscribers: function (data) {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/newsletter/subscribers', 'token=' + Journal2Config.token), data)
            },
            unsubscribe: function (data) {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/newsletter/unsubscribe', 'token=' + Journal2Config.token), data)
            },
            verifyCode: function(data) {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/data/verify_code', 'token=' + Journal2Config.token), data);
            },
            getBlog: function (url, data) {
                return Ajax.get(Url.generateLink('index.php?route=module/journal2/rest/blog/' + url, 'token=' + Journal2Config.token), data);
            },
            postBlog: function (url, data) {
                return Ajax.post(Url.generateLink('index.php?route=module/journal2/rest/blog/' + url, 'token=' + Journal2Config.token), data);
            },
            all: function(obj, success, error) {
                var promises = [];
                var response = {};

                angular.forEach(obj, function(o, i) {
                    promises.push(o.then(function(r){
                        response[i] = r;
                    }, function(e){
                        error(e);
                    }));
                });

                $q.all(promises).then(function(){
                    success(response);
                }, function(e){
                    error(e);
                });
            }
        };
    }]);

});
