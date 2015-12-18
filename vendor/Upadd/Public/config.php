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

return array(
    //记录程序开始执行时间
    'RUNTIME'=>true,
    //判断运行时间是否开启
    'IS_RUNTIME'=>true,
    //判断是否开启SESSION
    'APP_IS_SESSION'=>true,
    // 开发环境下请开发true,生产环境false/error_reporting()
    'APP_IS_ERROR'=>true,
    'IS_UPADD'=>VENDOR,
    'BIN'=>UPADD_HOST.VENDOR.'/Bin/',
    'IS_FRAME'=>'Frame/',
    'IS_EXPAND'=>'Expand/',
    //数据文件夹目录
    'data_dir'=>UPADD_HOST.'data/',
    'HTML_COMPILED_DIR'=>UPADD_HOST.'data/compiled/',
    'HTML_CACHE_DIR'=>UPADD_HOST.'data/cache/',
    'IMG_PATH'=>UPADD_HOST.'data/upload/',
    'HTML_TAG'=>true,
    'HTML_IS_CACHE'=>false,
    'log_path'=>UPADD_HOST.'data/log/',
    'CONF_DIR'=>UPADD_HOST.'config/',
    'UPADD_VERSION'=>'Upadd-0.5',
    'METHOD'=> (isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : ''),
    //判断是否启用插件
    'IS_PLUG'=>false,
);

