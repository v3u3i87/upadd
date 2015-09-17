<?php
/**
	+----------------------------------------------------------------------
	| UPADD [ Can be better to Up add]
	+----------------------------------------------------------------------
	| Copyright (c) 2011-2015 http://upadd.cn All rights reserved.
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
// set root dir
if(UP_RUN_MODE) {
    define ('UPADD_HOST', substr(dirname(__FILE__), 0, -12));
    define ('VENDOR', 'vendor/Upadd');
}else{
    define ('UPADD_HOST', substr(dirname(__FILE__), 0, -6));
    define ('VENDOR', '');
}

if(APP_DEBUG) {
    //引入报错文件
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

// 常量配置
require UPADD_HOST .VENDOR.'/Public/Define.inc.php';
// 函数库
require UPADD_HOST .VENDOR.'/Public/Function.inc.php';


\Upadd\Bin\Loader::Run();