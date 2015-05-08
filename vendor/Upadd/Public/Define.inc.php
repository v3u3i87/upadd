<?php
// 记录程序开始执行时间
defined ( 'RUNTIME' ) or define ( 'RUNTIME', microtime ( true ) );
// 判断运行时间是否开启
defined ( 'IS_RUNTIME' ) or define ( 'IS_RUNTIME', true );
// 功能目录
defined ( 'APP_WORK' ) or define ( 'APP_WORK', UPADD_HOST.'app/');
// 项目路径
defined ( 'APP_PAHT' ) or define ( 'APP_PAHT', APP_WORK . APP_NAME . '/' );
// 运行结构
defined ( 'RUN_DIR' ) or define ( 'RUN_DIR', 'Action,Check,Model,Logic,Html' );
// 设置SESSION
defined ( 'APP_IS_SESSION' ) or define ( 'APP_IS_SESSION', true );
// 开发环境下请开发true,生产环境false/error_reporting()
defined ( 'APP_IS_ERROR' ) or define ( 'APP_IS_ERROR', true );
// 核心目录
defined ( 'IS_UPADD' ) or define ( 'IS_UPADD', VENDOR );
//核心组件
defined ( 'BIN' ) or define ( 'BIN', UPADD_HOST.VENDOR.'/Bin/' );
// 架构MVC
defined ( 'IS_FRAME' ) or define ( 'IS_FRAME', IS_UPADD . 'Frame/' );
// 扩展
defined ( 'IS_EXPAND' ) or define ( 'IS_EXPAND', IS_UPADD . 'Expand/' );
// 项目目录
defined ( 'PROJECT' ) or define ( 'PROJECT', UPADD_HOST . APP_PAHT );
// 文件后缀
defined ( 'IS_EXP' ) or define ( 'IS_EXP', '.php' );
// 控制器路径
defined ( 'ACTION_PAHT' ) or define ( 'ACTION_PAHT', APP_PAHT . 'Action/' );
// 验证层路径
defined ( 'CHECK_PAHT' ) or define ( 'CHECK_PAHT', APP_PAHT . 'Check/' );
// 模板路径
defined ( 'HTML_PAHT' ) or define ( 'HTML_PAHT', APP_PAHT . 'Html/' );
// 逻辑层路径
defined ( 'LOGIC_PAHT' ) or define ( 'LOGIC_PAHT', APP_PAHT . 'Logic/' );
// 数据层路径
defined ( 'MODEL_PAHT' ) or define ( 'MODEL_PAHT', APP_PAHT . 'Model/' );
// 静态文件
defined ( 'STATIC_PATH' ) or define ( 'STATIC_PATH',  UPADD_HOST.'Data/Public/' . APP_NAME . '/' );
// 模板编译
defined ( 'HTML_COMPILED_DIR' ) or define ( 'HTML_COMPILED_DIR',   UPADD_HOST.'Data/Compiled/' . APP_NAME . '/' );
// 模板缓存
defined ( 'HTML_CACHE_DIR' ) or define ( 'HTML_CACHE_DIR',   UPADD_HOST.'Data/Cache/' . APP_NAME . '/' );
// 上传文件目录
defined ( 'IMG_PATH' ) or define ( 'IMG_PATH',  UPADD_HOST.'Data/Upload/' );
// 设置是否开启模板标签 true as false
defined ( 'HTML_TAG' ) or define ( 'HTML_TAG', true );
// 设置是否开启模板缓存true as false
defined ( 'HTML_IS_CACHE' ) or define ( 'HTML_IS_CACHE', false );
// 定义存在数据的文件夹名
defined ( 'DATA_DIR' ) or define ( 'DATA_DIR', UPADD_HOST.'Data/' );
// 日记常量
defined ( 'LOG_PATH' ) or define ( 'LOG_PATH',  UPADD_HOST.'Data/Log/' . APP_NAME . '/' );
// 配置文件
defined ( 'CONF_DIR' ) or define ( 'CONF_DIR',  UPADD_HOST.'config/' );
// 版本参数
defined ( 'UPADD_VERSION' ) or define ( 'UPADD_VERSION', 'Upadd-0.3' );
// 样式路径 Style
defined ( 'STYLE_PATH' ) or define ( 'STYLE_PATH',  UPADD_HOST.'Style/' );