<?php
//记录程序开始执行时间
defined('RUNTIME') or define('RUNTIME', microtime(true));

//判断运行时间是否开启
defined('IS_RUNTIME') or define('IS_RUNTIME', false);

//设置SESSION
defined('APP_IS_SESSION') or define('APP_IS_SESSION', true);

//开发环境下请开发true,生产环境false/error_reporting()
defined('APP_IS_ERROR') or define('APP_IS_ERROR', true);

//框架目录
defined('IS_UPADD') or define('IS_UPADD', UPADD_HOST.'/Upadd/Library/');

//核心目录
defined('IS_CORE') or define('IS_CORE', IS_UPADD.'Core/');

//架构MVC
defined('IS_FRAME') or define('IS_FRAME', IS_UPADD.'Frame/');

//扩展
defined('IS_EXPAND') or define('IS_EXPAND', IS_UPADD.'Expand/');

//项目目录
defined('PROJECT') or define('PROJECT', UPADD_HOST . APP_PAHT);

//文件后缀
defined('IS_EXP')  or define('IS_EXP', '.php');

//调用第三方类库
defined('UPADD_API')  or define('UPADD_API', UPADD_HOST.'/Upadd/Api/');

//控制器路径
defined('ACTION_PAHT')  or define('ACTION_PAHT', UPADD_HOST.APP_PAHT.'Action/');

//验证层路径
defined('CHECK_PAHT')  or define('CHECK_PAHT', UPADD_HOST.APP_PAHT.'Check/');

//模板路径
defined('HTML_PAHT')  or define('HTML_PAHT', UPADD_HOST.APP_PAHT.'Html/');

//逻辑层路径
defined('LOGIC_PAHT')  or define('LOGIC_PAHT', UPADD_HOST.APP_PAHT.'Logic/');

//数据层路径
defined('MODEL_PAHT')  or define('MODEL_PAHT', UPADD_HOST.APP_PAHT.'Model/');

//插件路径
defined('PLUGIN_PAHT')  or define('PLUGIN_PAHT', UPADD_HOST . '/Work/Plugins/');

//静态文件常量
defined('STATIC_PATH')  or define('STATIC_PATH',  'Data/Public/'.APP_NAME.'/');

//模板编译
defined('HTML_COMPILED_DIR')  or define('HTML_COMPILED_DIR', UPADD_HOST.'/Data/Compiled/'.APP_NAME.'/');

//模板缓存
defined('HTML_CACHE_DIR')  or define('HTML_CACHE_DIR', UPADD_HOST.'/Data/Cache/'.APP_NAME.'/');

//上传文件目录
defined('IMG_PATH')  or define('IMG_PATH', '/Data/Upload/');

//设置是否开启模板标签 true as false
defined('HTML_TAG') or define('HTML_TAG',true);

//设置是否开启模板缓存true as false
defined('HTML_IS_CACHE') or define('HTML_IS_CACHE', false);

//日记常量
defined('LOG_PATH')  or define('LOG_PATH', UPADD_HOST.'/Data/Log/'.APP_NAME.'/');

//版本参数
defined('UPADD_VERSION')  or define('UPADD_VERSION', 'Upadd-0.3');

//样式路径 Style
defined('STYLE_PATH')  or define('STYLE_PATH', '/Style/');