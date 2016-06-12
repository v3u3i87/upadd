<?php
/**
+----------------------------------------------------------------------
| upadd [ Can be better to up add]
+----------------------------------------------------------------------
| Copyright (c) 2011-2015 http://upadd.cn All rights reserved.
+----------------------------------------------------------------------
| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
+----------------------------------------------------------------------
| Author: Richard.z <v3u3i87@gmail.com>
 **/

define ( 'RUNTIME', microtime ( true ) );
define ( 'APP_NAME', 'demo' );
// 开启调试报错
define ( 'APP_DEBUG', false);
define ( 'APP_LANG', 'zh_cn' );
define ( 'APP_ROUTES', true );
//是否开启 SESSION
define ( 'IS_SESSION', false );




//加载composer in vendor
require __DIR__.'/vendor/autoload.php';
//加载Upadd
require __DIR__.'/vendor/Upadd/Upadd.conf.php';