<?php
/* @property ModelCatalogManufacturer model_catalog_manufacturer */
/* @property ModelCatalogCategory model_catalog_category */
class ControllerModuleJournal2CmsBlocks extends Controller {

    private static $CACHEABLE = null;

    protected $data = array();

    protected function render() {
        return Front::$IS_OC2 ? $this->load->view($this->template, $this->data) : parent::render();
    }

    public function __construct($registry) {
        parent::__construct($registry);
        if (!defined('JOURNAL_INSTALLED')) {
            return;
        }
        $this->load->model('journal2/module');

        if (self::$CACHEABLE === null) {
            self::$CACHEABLE = (bool)$this->journal2->settings->get('config_system_settings.cms_blocks_cache');
        }
    }

    public function index($setting) {
        if (!defined('JOURNAL_INSTALLED')) {
            return;
        }

        Journal2::startTimer(get_class($this));

        /* get module data from db */
        $module_data = $this->model_journal2_module->getModule($setting['module_id']);
        if (!$module_data || !isset($module_data['module_data']) || !$module_data['module_data']) return;
        $module_data = $module_data['module_data'];

        /* device detection */
        $this->data['disable_on_classes'] = array();

        if ($this->journal2->settings->get('responsive_design')) {
            $device = Journal2Utils::getDevice();

            if (Journal2Utils::getProperty($module_data, 'enable_on_phone', '1') == '0') {
                if ($device === 'phone') {
                    return;
                } else {
                    $this->data['disable_on_classes'][] = 'hide-on-phone';
                }
            }

            if (Journal2Utils::getProperty($module_data, 'enable_on_tablet', '1') == '0') {
                if ($device === 'tablet') {
                    return;
                } else {
                    $this->data['disable_on_classes'][] = 'hide-on-tablet';
                }
            }

            if (Journal2Utils::getProperty($module_data, 'enable_on_desktop', '1') == '0') {
                if ($device === 'desktop') {
                    return;
                } else {
                    $this->data['disable_on_classes'][] = 'hide-on-desktop';
                }
            }
        }

        $this->data['css'] = '';

        /* css for top / bottom positions */
        if (in_array($setting['position'], array('top', 'bottom'))) {
            $padding = $this->journal2->settings->get('module_margins', 20) . 'px';
            /* outer */
            $css = Journal2Utils::getBackgroundCssProperties(Journal2Utils::getProperty($module_data, 'background'));
            $css[] = 'padding-top: ' . Journal2Utils::getProperty($module_data, 'margin_top', 0) . 'px';
            $css[] = 'padding-bottom: ' . Journal2Utils::getProperty($module_data, 'margin_bottom', 0) . 'px';
            $this->journal2->settings->set('module_journal2_cms_blocks_' . $setting['module_id'], implode('; ', $css));
            $this->journal2->settings->set('module_journal2_cms_blocks_' . $setting['module_id'] . '_classes', implode(' ', $this->data['disable_on_classes']));

            /* inner css */
            $css = array();
            if (Journal2Utils::getProperty($module_data, 'fullwidth')) {
                $css[] = 'max-width: 100%';
                $css[] = 'padding-left: ' . $padding;
                $css[] = 'padding-right: ' . $padding;
            } else {
                $css[] = 'max-width: ' . $this->journal2->settings->get('site_width', 1024) . 'px';
                $css = array_merge($css, Journal2Utils::getBackgroundCssProperties(Journal2Utils::getProperty($module_data, 'module_background')));
                if (Journal2Utils::getProperty($module_data, 'module_padding')) {
                    $this->data['gutter_on_class'] = 'gutter-on';
                    $css[] = 'padding: 20px';
                }
            }
            $this->data['css'] = implode('; ', $css);
        }

        $cache_property = "module_journal_cms_blocks_{$setting['module_id']}_{$setting['layout_id']}_{$setting['position']}";

        $cache = $this->journal2->cache->get($cache_property);

        if ($cache === null || self::$CACHEABLE !== true) {
            $module = mt_rand();

            /* set global module properties */
            $this->data['module'] = $module;
            $this->data['title'] = Journal2Utils::getProperty($module_data, 'module_title.value.' . $this->config->get('config_language_id'), '');

            /* sort sections */
            $sections = Journal2Utils::getProperty($module_data, 'sections', array());
            $sections = Journal2Utils::sortArray($sections);

            /* generate sections */
            $this->data['sections'] = array();
            foreach ($sections as $section) {
                if (!$section['status']) continue;
                $css = array();

                if (Journal2Utils::getColor(Journal2Utils::getProperty($section, 'icon_bg_color.value.color'))) {
                    $css[] = 'background-color: ' . Journal2Utils::getColor(Journal2Utils::getProperty($section, 'icon_bg_color.value.color'));
                }
                if (Journal2Utils::getProperty($section, 'icon_width')) {
                    $css[] = 'width: ' . Journal2Utils::getProperty($section, 'icon_width') . 'px';
                }
                if (Journal2Utils::getProperty($section, 'icon_height')) {
                    $css[] = 'height: ' . Journal2Utils::getProperty($section, 'icon_height') . 'px';
                    $css[] = 'line-height: ' . Journal2Utils::getProperty($section, 'icon_height') . 'px';
                }
                if (Journal2Utils::getProperty($section, 'icon_border')) {
                    $css = array_merge($css, Journal2Utils::getBorderCssProperties(Journal2Utils::getProperty($section, 'icon_border')));
                }
                if ($color = Journal2Utils::getProperty($section, 'bg_color.value.color')) {
                    $color = 'background-color: ' . Journal2Utils::getColor($color);
                } else {
                    $color = null;
                }

                $this->data['sections'][] = array(
                    'css'   => $color,
                    'has_icon' => Journal2Utils::getProperty($section, 'icon_status'),
                    'icon_position' => Journal2Utils::getProperty($section, 'icon_position', 'top'),
                    'icon' => Journal2Utils::getIconOptions2(Journal2Utils::getProperty($section, 'icon')),
                    'icon_css' => implode('; ', $css),
                    'type' => 'html',
                    'title' => Journal2Utils::getProperty($section, 'section_title.value.' . $this->config->get('config_language_id'), ''),
                    'content_align' => Journal2Utils::getProperty($section, 'text_align', 'left'),
                    'content' => Journal2Utils::getProperty($section, 'text.' . $this->config->get('config_language_id'), 'Not Translated')
                );
            }

            /* grid classes */
            if (in_array($setting['position'], array('column_left', 'column_right'))) {
                $this->data['grid_classes'] = 'xs-100 sm-100 md-100 lg-100 xl-100';
            } else {
                $columns = in_array($setting['position'], array('top', 'bottom')) ? 0 : $this->journal2->settings->get('config_columns_count', 0);
                $this->data['grid_classes'] = Journal2Utils::getProductGridClasses(Journal2Utils::getProperty($module_data, 'items_per_row.value'), $this->journal2->settings->get('site_width', 1024), $columns);
            }

            $this->template = $this->config->get('config_template') . '/template/journal2/module/cms_blocks.tpl';

            if (self::$CACHEABLE === true) {
                $html = Minify_HTML::minify($this->render(), array(
                    'xhtml' => false,
                    'jsMinifier' => 'j2_js_minify'
                ));
                $this->journal2->cache->set($cache_property, $html);
            }
        } else {
            $this->template = $this->config->get('config_template') . '/template/journal2/cache/cache.tpl';
            $this->data['cache'] = $cache;
        }

        $output = $this->render();

        Journal2::stopTimer(get_class($this));

        return $output;
    }

}
