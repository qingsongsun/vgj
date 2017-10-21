<?php  
class ControllerModuleJournal2PhotoGallery extends Controller {

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
        $this->load->model('tool/image');

        if (self::$CACHEABLE === null) {
            self::$CACHEABLE = (bool)$this->journal2->settings->get('config_system_settings.photo_gallery_cache');
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
            $this->journal2->settings->set('module_journal2_photo_gallery_' . $setting['module_id'], implode('; ', $css));
            $this->journal2->settings->set('module_journal2_photo_gallery_' . $setting['module_id'] . '_classes', implode(' ', $this->data['disable_on_classes']));

            /* inner css */
            $css = array();
            if (Journal2Utils::getProperty($module_data, 'fullwidth')) {
                $css[] = 'max-width: 100%';
                $css[] = 'padding-left: ' . $padding;
                $css[] = 'padding-right: ' . $padding;
            } else {
                $css[] = 'max-width: ' . $this->journal2->settings->get('site_width', 1024) . 'px';
            }
            $this->data['css'] = implode('; ', $css);
        }

        $cache_property = "module_journal_photo_gallery_{$setting['module_id']}_{$setting['layout_id']}_{$setting['position']}";

        $cache = $this->journal2->cache->get($cache_property);

        if ($cache === null || self::$CACHEABLE !== true) {
            $module = mt_rand();

            /* set global module properties */
            $this->data['module'] = $module;
            $this->data['title'] = Journal2Utils::getProperty($module_data, 'gallery_name.value.' . $this->config->get('config_language_id'), '');

            /* image border */
            $this->data['image_border'] = implode('; ', Journal2Utils::getBorderCssProperties(Journal2Utils::getProperty($module_data, 'image_border')));

            /* grid */
            $this->data['is_column'] = in_array($setting['position'], array('column_left', 'column_right'));

            if (in_array($setting['position'], array('column_left', 'column_right'))) {
                $this->data['grid_classes'] = 'xs-33 sm-33 md-33 lg-33 xl-33';
            } else {
                $columns = in_array($setting['position'], array('top', 'bottom')) ? 0 : $this->journal2->settings->get('config_columns_count', 0);
                $this->data['grid_classes'] = Journal2Utils::getProductGridClasses(Journal2Utils::getProperty($module_data, 'items_per_row.value'), $this->journal2->settings->get('site_width', 1024), $columns);
            }

            /* carousel */
            $this->data['carousel'] = in_array($setting['position'], array('column_left', 'column_right')) ? false : Journal2Utils::getProperty($module_data, 'carousel');
            if ($this->data['carousel']) {
                $this->data['items_per_row'] = Journal2Utils::getProperty($module_data, 'items_per_row.value');
                $this->data['arrows'] = Journal2Utils::getProperty($module_data, 'carousel_arrows');
                $this->data['bullets'] = Journal2Utils::getProperty($module_data, 'carousel_bullets');
                if (Journal2Utils::getProperty($module_data, 'autoplay')) {
                    $this->data['autoplay'] = Journal2Utils::getProperty($module_data, 'transition_delay', 3000);
                } else {
                    $this->data['autoplay'] = false;
                }
                $this->data['slide_speed'] = (int)Journal2Utils::getProperty($module_data, 'transition_speed', 400);
                $this->data['pause_on_hover'] = Journal2Utils::getProperty($module_data, 'pause_on_hover');
                $this->data['touch_drag'] = Journal2Utils::getProperty($module_data, 'touch_drag');
            }

            /* sort images */
            $images = Journal2Utils::getProperty($module_data, 'images', array());
            $images = Journal2Utils::sortArray($images);

            /* generate images */
            $this->data['thumbs_limit']  = Journal2Utils::getProperty($module_data, 'thumbs_limit', PHP_INT_MAX);
            $this->data['thumbs_width']  = Journal2Utils::getProperty($module_data, 'thumbs_width', 200);
            $this->data['thumbs_height'] = Journal2Utils::getProperty($module_data, 'thumbs_height', 200);
            $this->data['thumbs_type']   = Journal2Utils::getProperty($module_data, 'thumbs_type', 'crop');
            $this->data['images'] = array();
            foreach ($images as $image) {
                if (isset($image['status']) && !$image['status']) continue;
                $this->data['images'][] = array(
                    'name'      => Journal2Utils::getProperty($image, 'name.value.' . $this->config->get('config_language_id'), ''),
                    'image'     => Journal2Utils::resizeImage($this->model_tool_image, $image),
                    'thumb'     => Journal2Utils::resizeImage($this->model_tool_image, $image, $this->data['thumbs_width'], $this->data['thumbs_height'], $this->data['thumbs_type']),
                );
            }

            $this->template = $this->config->get('config_template') . '/template/journal2/module/photo_gallery.tpl';

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

        $this->document->addScript('catalog/view/theme/journal2/lib/swipebox/source/jquery.swipebox.js');

        $output = $this->render();

        Journal2::stopTimer(get_class($this));

        return $output;
    }
}
