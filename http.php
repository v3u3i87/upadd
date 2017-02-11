<?php

define('RUNTIME', microtime(true));
define('APP_NAME', 'http');
define('APP_LANG', 'zh_cn');
define('APP_ROUTES', true);

//加载composer in vendor
require __DIR__.'/vendor/autoload.php';
//加载Upadd
require __DIR__.'/vendor/Upadd/Upadd.conf.php';

use Upadd\Swoole\HttpServer;

$swooleHtpp = Config::get('swoole@http');

HttpServer::create($swooleHtpp['name'],$swooleHtpp['host'])->start();