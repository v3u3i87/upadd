<?php
/**
 * +----------------------------------------------------------------------
 * | UPADD [ Can be better to Up add]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2011-2018 http://upadd.cn All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: Richard.z <v3u3i87@gmail.com>
 **/

if (version_compare(PHP_VERSION, '7.0.0', '<')) exit ('require PHP > 7.0.0!');
define('VENDOR', '/vendor/upadd');
define('UPADD_HOST', substr(dirname(__FILE__), 0, -17));

// 函数库
require UPADD_HOST . VENDOR . '/src/public/help.php';


/**
 * 实例化APP
 */
$app = \Upadd\Bin\Application::init();
$app->execute();