<?php

/**
| Author: Richard.z <v3u3i87@gmail.com>
 **/
define('RUNTIME', microtime(true));
define('APP_NAME', 'demo');
define('APP_LANG', 'zh_cn');
define('APP_ROUTES', true);

//加载composer in vendor
require __DIR__.'/vendor/autoload.php';
//加载Upadd
require __DIR__.'/vendor/Upadd/Upadd.conf.php';
