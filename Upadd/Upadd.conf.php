<?php
/**
	+----------------------------------------------------------------------
	| UPADD [ Can be better to Up add]
	+----------------------------------------------------------------------
	| Copyright (c) 20011-2014 http://upadd.cn All rights reserved.
	+----------------------------------------------------------------------
	| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
	+----------------------------------------------------------------------
	| Author: Richard.z <v3u3i87@gmail.com>
 **/
if(version_compare(PHP_VERSION,'5.3.0','<')) die('require PHP > 5.3.0 !');
//设置根路径
define('UPADD_HOST',substr(dirname(__FILE__),0,-6));
//设置编码
header('Content-Type:text/html;charset=utf-8');
//设置时区
date_default_timezone_set('Asia/Shanghai');
//常量配置
require UPADD_HOST.'/Upadd/Common/Define.inc.php';
//函数库
require UPADD_HOST.'/Upadd/Common/Function.inc.php';
if(APP_IS_ERROR) error_reporting(E_ALL) ? error_reporting(0) : null;
if(APP_IS_SESSION) session_start();
file_exists(IS_CORE.'DataLog.class.php') ?  require IS_CORE.'DataLog.class.php' : exit('调用日记错误');
file_exists(IS_CORE.'UpaddException.class.php') ?  require IS_CORE.'UpaddException.class.php' : exit('调用异常处理错误');
file_exists(IS_UPADD.'Upadd.class.php')  ? require IS_UPADD.'Upadd.class.php' : exit('程序异常!');
Upadd::UpStart();	