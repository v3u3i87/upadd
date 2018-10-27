<?php
/**
 * | php console.php --u=test --p=main
 **/
ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit', '512M');
define('RUNTIME', microtime(true));
//项目名称
define('APP_NAME', 'console');
// 开启调试报错
define('APP_DEBUG', true);
define('APP_LANG', 'zh_cn');
/*
 * 定义传统形式访问
 */
define('APP_ROUTES', false);

//加载composer in vendor
require __DIR__ . '/vendor/autoload.php';
//加载Upadd
require __DIR__ . '/vendor/upadd/src/run.php';

$app->dispenser->console(isset($argv) ? $argv : []);