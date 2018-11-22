<?php

/**
| Author: Richard.z <v3u3i87@gmail.com>
 **/
define('RUNTIME', microtime(true));
define('APP_NAME', 'demo');
define('APP_LANG', 'zh_cn');
define('APP_ROUTES', true);

//加载composer in vendor
require  '../vendor/autoload.php';
//加载Upadd
require '../vendor/upadd/src/run.php';

$app->loadCig();