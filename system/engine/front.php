<?php
final class Front {
	protected $registry;
	protected $pre_action = array();
	protected $error;

    /* Journal2 Theme modification */
    public static $IS_INSTALLER = false;
    public static $IS_JOURNAL   = false;
    public static $IS_OC2       = false;
    public static $IS_ADMIN     = false;
    /* End of Journal2 Theme modification */

    public function __construct($registry) {
        $this->registry = $registry;

        /* Journal2 Theme modification */
        if (defined('HTTP_OPENCART')) {
            self::$IS_INSTALLER = true;
        } else if (defined('VERSION')) {
            global $config;
            self::$IS_JOURNAL   = $config->get('config_template') === 'journal2';
            self::$IS_OC2       = version_compare(VERSION, '2', '>=');
            if (defined('HTTP_CATALOG') || defined('HTTPS_CATALOG')) {
                $url1 = new Url(HTTP_SERVER, HTTPS_SERVER);
                $url2 = new Url(defined('HTTP_CATALOG') ? HTTP_CATALOG : HTTPS_CATALOG, defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTP_CATALOG);
                $url1 = parse_url($url1->link(''));
                $url2 = parse_url($url2->link(''));
                self::$IS_ADMIN = $url1['host'] . $url1['path'] !== $url2['host'] . $url2['path'];
            }
        }
        /* End of Journal2 Theme modification */
    }

	public function addPreAction($pre_action) {
		$this->pre_action[] = $pre_action;
	}

  	public function dispatch($action, $error) {
		$this->error = $error;

        /* Journal2 Theme modification */
        if (self::$IS_ADMIN || !(isset($this->registry->get('request')->get['route']) && in_array($this->registry->get('request')->get['route'], array('journal2/assets/css', 'journal2/assets/js')))) {
        /* End of Journal2 Theme modification */
            foreach ($this->pre_action as $pre_action) {
                $result = $this->execute($pre_action);

                if ($result) {
                    $action = $result;

                    break;
                }
            }
        /* Journal2 Theme modification */
        }
        /* End of Journal2 Theme modification */

        /* Journal2 Theme modification */
        require_once(DIR_SYSTEM . 'journal2/startup.php');
        /* End of Journal2 Theme modification */

		while ($action) {
			$action = $this->execute($action);
		}
  	}

	private function execute($action) {
        if (method_exists($action, 'getFile')) {
            if (file_exists($action->getFile())) {
                require_once($action->getFile());

                $class = $action->getClass();

                $controller = new $class($this->registry);

                if (is_callable(array($controller, $action->getMethod()))) {
                    $action = call_user_func_array(array($controller, $action->getMethod()), $action->getArgs());
                } else {
                    $action = $this->error;

                    $this->error = '';
                }
            } else {
                $action = $this->error;

                $this->error = '';
            }
        } else {
            $result = $action->execute($this->registry);

            if (is_object($result)) {
                $action = $result;
            } elseif ($result === false) {
                $action = $this->error;

                $this->error = '';
            } else {
                $action = false;
            }
        }

		return $action;
	}
}
?>