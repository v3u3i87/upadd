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
| Command Line the explanation demo
| php console.php --u=test --p=main
 **/
ini_set('max_execution_time', 0);
set_time_limit(0);
define ( 'RUNTIME', microtime ( true ) );
//项目名称
define ( 'APP_NAME', 'console' );
// 开启调试报错
define ( 'APP_DEBUG', true);
define ( 'APP_LANG', 'zh_cn' );
/**
 * 定义传统形式访问
 */
define ( 'APP_ROUTES', false );
/**
 * 是否启用SESSION
 */
define ( 'IS_SESSION', false );


//加载composer in vendor
require __DIR__.'/vendor/autoload.php';
//加载Upadd
require __DIR__.'/vendor/Upadd/Upadd.conf.php';