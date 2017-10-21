<?php
class ControllerModuleJournal2BlogTags extends Controller {

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
        $this->load->model('journal2/blog');

        if (self::$CACHEABLE === null) {
            self::$CACHEABLE = (bool)$this->journal2->settings->get('config_system_settings.blog_tags_cache');
        }
    }

    public function index($setting) {
        if (!defined('JOURNAL_INSTALLED')) {
            return;
        }

        if (!$this->model_journal2_blog->isEnabled()) {
            return;
        }

        Journal2::startTimer(get_class($this));

        /* get module data from db */
        $module_data = $this->model_journal2_module->getModule($setting['module_id']);
        if (!$module_data || !isset($module_data['module_data']) || !$module_data['module_data']) return;

        if (Journal2Cache::$mobile_detect->isMobile() && !Journal2Cache::$mobile_detect->isTablet() && $this->journal2->settings->get('responsive_design')) return;

        $hash = isset($this->request->server['REQUEST_URI']) ? md5($this->request->server['REQUEST_URI']) : null;

        $cache_property = "module_journal_blog_tags_{$setting['module_id']}_{$setting['layout_id']}_{$setting['position']}_{$hash}";

        $cache = $this->journal2->cache->get($cache_property);

        if ($cache === null || self::$CACHEABLE !== true || $hash === null) {
            $module = mt_rand();

            $this->data['module'] = $module;
            $this->data['heading_title'] = Journal2Utils::getProperty($module_data, 'module_data.title.value.' . $this->config->get('config_language_id'), 'Not Translated');

            $this->data['tags'] = array();
            $tags = $this->model_journal2_blog->getTags(Journal2Utils::getProperty($module_data, 'module_data.limit', 15));
            foreach ($tags as $tag) {
                $this->data['tags'][] = array(
                    'name'  => $tag,
                    'href'  => $this->url->link('journal2/blog', 'journal_blog_tag=' . $tag)
                );
            }

            $this->template = $this->config->get('config_template') . '/template/journal2/module/blog_tags.tpl';

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
