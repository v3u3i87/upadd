<?php
set_time_limit(0);
define ( 'RUNTIME', microtime ( true ) );
define ( 'APP_IS_LOG', true ); // 开启日记记录
define ( 'APP_DEBUG', true ); // 开启调试报错
define ( 'APP_NAME', 'Home' ); // 设置项目名称
define ( 'UP_RUN_MODE', 1 ); // 设置运入模式
// 程序全局语言包
define ( 'APP_LANG', 'zh_cn' );

require '../bootstrap/load.php';
