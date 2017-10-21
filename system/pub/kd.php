<?php

// the php for cross domain ajax
$param = http_build_query($_GET);
$api   = 'http://www.aikuaidi.cn/rest/?'.$param;

echo file_get_contents($api);

?>