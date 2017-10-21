<?php
class ControllerModuleJournal2BlogComments extends Controller {

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
        $this->load->model('tool/image');

        if (self::$CACHEABLE === null) {
            self::$CACHEABLE = (bool)$this->journal2->settings->get('config_system_settings.blog_comments_cache');
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

        $cache_property = "module_journal_blog_comments_{$setting['module_id']}_{$setting['layout_id']}_{$setting['position']}_{$hash}";

        $cache = $this->journal2->cache->get($cache_property);

        if ($cache === null || self::$CACHEABLE !== true || $hash === null) {
            $module = mt_rand();

            $this->data['module'] = $module;
            $this->data['heading_title'] = Journal2Utils::getProperty($module_data, 'module_data.title.value.' . $this->config->get('config_language_id'), 'Not Translated');
            $this->data['default_author_image'] = $this->model_tool_image->resize('data/journal2/misc/avatar.png', 75, 75);

            $this->data['comments'] = array();
            $comments = $this->model_journal2_blog->getLatestComments(Journal2Utils::getProperty($module_data, 'module_data.limit', 5));
            $char_limit = Journal2Utils::getProperty($module_data, 'module_data.char_limit', 50);
            foreach ($comments as $comment) {
                $this->data['comments'][] = array(
                    'email'     => $comment['email'],
                    'name'      => $comment['name'],
                    'comment'   => utf8_substr(strip_tags(html_entity_decode($comment['comment'], ENT_QUOTES, 'UTF-8')), 0, $char_limit) . '...',
                    'post'      => $comment['post'],
                    'href'      => $this->url->link('journal2/blog/post', 'journal_blog_post_id=' . $comment['post_id']) . '#c' . $comment['comment_id']
                );
            }

            $this->template = $this->config->get('config_template') . '/template/journal2/module/blog_comments.tpl';

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
