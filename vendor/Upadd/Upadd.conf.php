<?php
/**
+----------------------------------------------------------------------
| UPADD [ Can be better to Up add]
+----------------------------------------------------------------------
| Copyright (c) 2011-2016 http://upadd.cn All rights reserved.
+----------------------------------------------------------------------
| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
+----------------------------------------------------------------------
| Author: Richard.z <v3u3i87@gmail.com>
 **/
if (version_compare ( PHP_VERSION, '5.4.0', '<' ))	die ( 'require PHP > 5.3.0 !' );
// 设置编码
header ( 'Content-Type:text/html;charset=utf-8' );
// 设置时区
date_default_timezone_set ( 'Asia/Shanghai' );

define ('VENDOR', 'vendor/Upadd');
define ('UPADD_HOST', substr(dirname(__FILE__), 0, -12));

// 函数库
require UPADD_HOST . VENDOR .'/Public/help.php';

if(APP_DEBUG)
{
    //引入报错文件
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

use Upadd\Bin\Factory;

/**
 * 实例化APP
 */
$app = new \Upadd\Bin\Application();



/**
 * 设置配置文件
 */
$app->getConfig();

/**
 * 创建目录
 */
$app->is_create_data_dir();

/**
 * 设置Session
 */
$app->setSession();


/**
 * 实例化模块
 */
$app->getWorkModule();

/**
 * 导入实例化模块
 */
Factory::Import($app->_work);

/**
 * 载入别名
 */
$app->getAlias()->run();



/**
 * 开始工作
 */
$app->run(function() use ($app)
{

    $_hostConfigPath = host().'config';

    /**
     * 扩展文件
     */
    $extend = $_hostConfigPath.'/extend.php';
    if(file_exists($extend)) require $extend;

    /**
     * 路由配置
     */
    $routing = $_hostConfigPath . '/routing.php';
    if (file_exists($routing)) require $routing;

    /**
     * 过滤器
     */
    $filters = $_hostConfigPath . '/filters.php';
    if (file_exists($filters)) require $filters;

},isset($argv) ? $argv : array());