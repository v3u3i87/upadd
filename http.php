<?php

set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');

define('RUNTIME', microtime(true));
define('APP_NAME', 'http');
define('APP_LANG', 'zh_cn');
define('APP_ROUTES', true);

//加载composer in vendor
require __DIR__.'/vendor/autoload.php';
//加载Upadd
require __DIR__ . '/vendor/upadd/src/run.php';

use Upadd\Swoole\HttpServer;

$http = Config::get('swoole@http');

HttpServer::create($http['name'],$http['host'])->start();