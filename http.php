<?php

define('RUNTIME', microtime(true));
define('APP_NAME', 'http');
define('APP_LANG', 'zh_cn');
define('APP_ROUTES', true);
//是否开启 SESSION
define('IS_SESSION', false);
define('IS_SWOOLE_HTTP', true);

//加载composer in vendor
require __DIR__.'/vendor/autoload.php';

//加载Upadd
require __DIR__.'/vendor/Upadd/Upadd.conf.php';