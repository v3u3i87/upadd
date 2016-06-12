<?php
/**
+----------------------------------------------------------------------
| upadd [ Can be better to up add]
+----------------------------------------------------------------------
| Copyright (c) 2011-2016 https://github.com/v3u3i87/upadd All rights reserved.
+----------------------------------------------------------------------
| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
+----------------------------------------------------------------------
| Author: Richard.z <v3u3i87@gmail.com>
 **/

define ( 'RUNTIME', microtime ( true ) );
define ( 'APP_NAME', 'console' );
define ( 'APP_DEBUG', true); // 开启调试报错
define ( 'APP_LANG', 'zh_cn' );
define ( 'APP_ROUTES', false );
define ( 'IS_SESSION', false );


//加载composer in vendor
require __DIR__.'/vendor/autoload.php';
//加载Upadd
require __DIR__.'/vendor/Upadd/Upadd.conf.php';