<?php
class ControllerJournal2Snippets extends Controller {

    const FB_IMG_WIDTH          = 600;
    const FB_IMG_HEIGHT         = 315;
    const TWITTER_IMG_WIDTH     = 200;
    const TWITTER_IMG_HEIGHT    = 200;

    protected $data = array();

    private $s_type         = null;
    private $s_title        = null;
    private $s_description  = null;
    private $s_url          = null;
    private $s_image        = null;

    protected function render() {
        return Front::$IS_OC2 ? $this->load->view($this->template, $this->data) : parent::render();
    }

    public function index() {
        $this->load->model('tool/image');

        /* blog manager compatibility */
        $route = isset($this->request->get['route']) ? $this->request->get['route'] : null;
        if ($route !== null && in_array($route, array('blog/article', 'blog/category'))) {
            return;
        }
        /* end of blog manager compatibility */

        /* default values */
        $this->s_type = 'website';
        $this->s_title = $this->config->get('config_name');
        $meta_description = $this->config->get('config_meta_description');
        if (is_array($meta_description)) {
            $lang_id = $this->config->get('config_language_id');
            if (isset($meta_description[$lang_id])) {
                $this->s_description = $meta_description[$lang_id] . '...';
            }
        } else {
            $this->s_description = $meta_description  . '...';
        }
        $this->s_url = Journal2Cache::getCurrentUrl();
        $this->s_image = $this->config->get('config_logo');

        /* overwrite values */
        switch ($this->journal2->page->getType()) {
            case 'product':
                $this->load->model('catalog/product');
                $product_info = $this->model_catalog_product->getProduct($this->journal2->page->getId());
                if ($product_info) {
                    $this->s_type = 'product';
                    $this->s_title = $product_info['name'];
                    $this->s_description = trim(utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 300));
                    $this->s_image = $product_info['image'];
                    $this->s_url = $this->url->link('product/product', 'product_id=' . $this->journal2->page->getId());

                    $this->journal2->settings->set('product_google_snippet', 'itemscope itemtype="http://schema.org/Product"');
                    $this->journal2->settings->set('product_price_currency', $this->currency->getCode());
                    $this->journal2->settings->set('product_num_reviews', $product_info['reviews']);
                    $this->journal2->settings->set('product_in_stock', $product_info['quantity'] > 0 ? 'yes' : 'no');
                    /* review ratings */
                    $this->language->load('product/product');

                    $this->load->model('catalog/review');

                    $this->data['text_on'] = $this->language->get('text_on');
                    $this->data['text_no_reviews'] = $this->language->get('text_no_reviews');

                    if (isset($this->request->get['page'])) {
                        $page = $this->request->get['page'];
                    } else {
                        $page = 1;
                    }

                    $this->data['reviews'] = array();

                    $review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

                    $results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

                    foreach ($results as $result) {
                        $this->data['reviews'][] = array(
                            'author'     => $result['author'],
                            'text'       => $result['text'],
                            'rating'     => (int)$result['rating'],
                            'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
                            'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
                        );
                    }

                    $pagination = new Pagination();
                    $pagination->total = $review_total;
                    $pagination->page = $page;
                    $pagination->limit = 5;
                    $pagination->text = $this->language->get('text_pagination');
                    $pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

                    $this->data['pagination'] = $pagination->render();

                    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/review.tpl')) {
                        $this->template = $this->config->get('config_template') . '/template/product/review.tpl';
                    } else {
                        $this->template = 'default/template/product/review.tpl';
                    }

                    $this->journal2->settings->set('product_reviews', $this->render());
                }
                break;

            case 'category':
                $this->load->model('catalog/category');
                $parts = explode('_', $this->journal2->page->getId());
                $category_id = (int)array_pop($parts);
                $category_info = $this->model_catalog_category->getCategory($category_id);
                if ($category_info) {
                    $this->s_title = $category_info['name'];
                    $this->s_description = trim(utf8_substr(strip_tags(html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8')), 0, 300));
                    $this->s_image = $category_info['image'];
                    $this->s_url = $this->url->link('product/category', 'path=' . $category_id);
                }
                break;

            case 'journal-blog-post':
                $this->load->model('journal2/blog');
                $post_info = $this->model_journal2_blog->getPost($this->journal2->page->getId());
                $this->s_title = Journal2Utils::getProperty($post_info, 'name');
                $this->s_description = trim(utf8_substr(strip_tags(html_entity_decode(Journal2Utils::getProperty($post_info, 'description'), ENT_QUOTES, 'UTF-8')), 0, 300));
                $this->s_image = Journal2Utils::getProperty($post_info, 'image');
                $this->s_url = $this->url->link('journal2/blog/post', 'journal_blog_post_id=' . $this->journal2->page->getId());
                break;
        }

        $metas = array();

        // Facebook
        $metas[] = array('type' => 'og:title'       , 'content' => $this->s_title);
        $metas[] = array('type' => 'og:site_name'   , 'content' => $this->config->get('config_name'));
        $metas[] = array('type' => 'og:url'         , 'content' => $this->s_url);
        $metas[] = array('type' => 'og:description' , 'content' => $this->s_description);
        $metas[] = array('type' => 'og:type'        , 'content' => $this->s_type);
        $metas[] = array('type' => 'og:image'       , 'content' => Journal2Utils::resizeImage($this->model_tool_image, $this->s_image, self::FB_IMG_WIDTH, self::FB_IMG_HEIGHT, 'fit'));
        $metas[] = array('type' => 'og:image:width' , 'content' => self::FB_IMG_WIDTH);
        $metas[] = array('type' => 'og:image:height', 'content' => self::FB_IMG_HEIGHT);

        // Twitter
        $metas[] = array('type' => 'twitter:card'           , 'content' => 'summary');
        $metas[] = array('type' => 'twitter:title'          , 'content' => $this->s_title);
        $metas[] = array('type' => 'twitter:description'    , 'content' => $this->s_description);
        $metas[] = array('type' => 'twitter:image'          , 'content' => Journal2Utils::resizeImage($this->model_tool_image, $this->s_image, self::TWITTER_IMG_WIDTH, self::TWITTER_IMG_HEIGHT, 'fit'));
        $metas[] = array('type' => 'twitter:image:width'    , 'content' => self::TWITTER_IMG_WIDTH);
        $metas[] = array('type' => 'twitter:image:height'   , 'content' => self::TWITTER_IMG_HEIGHT);

        $this->journal2->settings->set('share_metas', $metas);
    }

}