<?php
class Session {
	public $data = array();

	public function __construct() {
		// $redis=new Redis();
		// $redis->connect('127.0.0.1',6379);
		if (!session_id()) {
			ini_set('session.use_only_cookies', 'Off');
			ini_set('session.use_trans_sid', 'On');
			ini_set('session.cookie_httponly', 'Off');

			// ini_set("session.save_handler","redis");
			// ini_set("session.save_path","tcp://127.0.0.1:6379");


			session_set_cookie_params(0, '/');

			if (isset($_GET['session_id'])) {
				session_id($_GET['session_id']);
			}

			// if ($redis->get('session_id')) {
			// 	session_id($redis->get('session_id'));
			// }


			session_start();
		}

		$this->data =& $_SESSION;
	}

	public function getId() {
		return session_id();
	}

	public function destroy() {
		return session_destroy();
	}
}