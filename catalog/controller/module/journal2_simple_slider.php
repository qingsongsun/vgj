<?php  
class ControllerModuleJournal2SimpleSlider extends Controller {

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
        $this->load->model('journal2/menu');
        $this->load->model('tool/image');
        $this->load->model('catalog/product');
        $this->load->model('catalog/category');
        $this->load->model('catalog/manufacturer');
        $this->load->model('catalog/information');

        if (self::$CACHEABLE === null) {
            self::$CACHEABLE = (bool)$this->journal2->settings->get('config_system_settings.simple_slider_cache');
        }
    }

    public function index($setting) {
        if (!defined('JOURNAL_INSTALLED')) {
            return;
        }

        Journal2::startTimer(get_class($this));

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

        /* css for top / bottom positions */
        if (in_array($setting['position'], array('top', 'bottom'))) {
            $padding = $this->journal2->settings->get('module_margins', 20) . 'px';
            /* outer */
            $css = Journal2Utils::getBackgroundCssProperties(Journal2Utils::getProperty($module_data, 'background'));
            $css[] = 'padding-top: ' . Journal2Utils::getProperty($module_data, 'margin_top', 0) . 'px';
            $css[] = 'padding-bottom: ' . Journal2Utils::getProperty($module_data, 'margin_bottom', 0) . 'px';
            $this->journal2->settings->set('module_journal2_simple_slider_' . $setting['module_id'], implode('; ', $css));
            $this->journal2->settings->set('module_journal2_simple_slider_' . $setting['module_id'] . '_classes', implode(' ', $this->data['disable_on_classes']));
        }

        $cache_property = "module_journal_simple_slider_{$setting['module_id']}_{$setting['layout_id']}_{$setting['position']}";

        $cache = $this->journal2->cache->get($cache_property);

        if ($cache === null || self::$CACHEABLE !== true) {
            $module = mt_rand();

            /* slider position */
            $height = Journal2Utils::getProperty($module_data, 'height', 400);
            $width = null;
            switch ($setting['position']) {
                case 'column_left':
                case 'column_right':
                    $width = 220;
                    $this->data['width'] = "max-width: {$width}px";
                    break;
                case 'content_top':
                case 'content_bottom':
                    if (Journal2Cache::$mobile_detect->isMobile() && !Journal2Cache::$mobile_detect->isTablet() && $this->journal2->settings->get('responsive_design')) {
                        $width = $this->journal2->settings->get('site_width', 1024);
                    } else {
                        if ($this->journal2->settings->get('extended_layout')) {
                            $width = $this->journal2->settings->get('site_width', 1024) - 240 * $this->journal2->settings->get('config_columns_count');
                        } else {
                            $width = $this->journal2->settings->get('site_width', 1024) - 40 - 240 * $this->journal2->settings->get('config_columns_count');
                        }
                        $height *= $width / $this->journal2->settings->get('site_width', 1024);
                    }
                    $this->data['width'] = "max-width: {$width}px";
                    break;
                case 'top':
                case 'bottom':
                    $width = $this->journal2->settings->get('site_width', 1024);
                    $this->data['width'] = "max-width: {$width}px";
                    break;
                case 'multi_module':
                    $width = $setting['width'];
                    $height = $setting['height'];
                    $this->data['width'] = "max-width: {$width}px";
                    break;
            }

            /* global style data */
            $this->data['global_style'] = array();

            $slides = Journal2Utils::getProperty($module_data, 'slides', array());
            $slides = Journal2Utils::sortArray($slides);
            $_slides = array();

            $this->data['js_options'] = array(
                'slideSpeed'        => (int)Journal2Utils::getProperty($module_data, 'transition_speed', 800),
                'stopOnHover'       => (bool)Journal2Utils::getProperty($module_data, 'pause_on_hover', 1),
                'lazyLoad'          => (bool)Journal2Utils::getProperty($module_data, 'preload_images', 1),
                'touchDrag'         => (bool)Journal2Utils::getProperty($module_data, 'touch_drag', 0),
                'navigation'        => (bool)Journal2Utils::getProperty($module_data, 'arrows', 1),
                'pagination'        => (bool)Journal2Utils::getProperty($module_data, 'bullets', 1)
            );

            if (Journal2Utils::getProperty($module_data, 'transition', 'fade') !== 'slide') {
                $this->data['js_options']['transitionStyle'] = Journal2Utils::getProperty($module_data, 'transition', 'fade');
            }

            $this->data['nav_on_hover'] = Journal2Utils::getProperty($module_data, 'show_on_hover', 1) ? 'nav-on-hover' : '';

            if (Journal2Utils::getProperty($module_data, 'autoplay')) {
                $this->data['js_options']['autoPlay'] = (int)Journal2Utils::getProperty($module_data, 'transition_delay', 3000);
            } else {
                $this->data['js_options']['autoPlay'] = false;
            }

            $this->data['image_width']  = $width;
            $this->data['image_height'] = $height;

            foreach ($slides as $slide) {
                if (isset($slide['status']) && !$slide['status']) continue;
                $image = Journal2Utils::getProperty($slide, 'image');
                if (is_array($image)) {
                    $image = Journal2Utils::getProperty($image, $this->config->get('config_language_id'));
                }
                $_slides[] = array(
                    'image'     => Journal2Utils::resizeImage($this->model_tool_image, $image, $width, $height, 'crop'),
                    'name'      => Journal2Utils::getProperty($slide, 'slide_name'),
                    'link'      => $this->model_journal2_menu->getLink(Journal2Utils::getProperty($slide, 'link')),
                    'target'    => Journal2Utils::getProperty($slide, 'link_new_window') ? 'target="_blank"' : ''
                );
            }

            $this->data['slides'] = $_slides;
            if (count($_slides) <= 1) {
                $this->data['js_options']['autoPlay'] = false;
            }

            $this->data['module'] = $module;
            $this->data['preload_images'] = Journal2Utils::getProperty($module_data, 'preload_images', '1');
            $this->data['height'] = $height;

            $this->template = $this->config->get('config_template') . '/template/journal2/module/slider_simple.tpl';

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
