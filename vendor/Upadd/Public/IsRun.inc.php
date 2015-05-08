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
 | FileName:程序运行核心判断
 **/

if(!isRunMachineName()){
    is_exit(json(array('code'=>10004,'msg'=>lang('loadRunConfig'),'data'=>array())));
}

// 判断目录结构
is_dirName(APP_WORK);
// 项目目录
is_dirName(APP_PAHT);
$dir = lode(',', RUN_DIR);
foreach ($dir as $v) {
    is_dirName(APP_PAHT . $v);
}

// 数据资源文件夹
if (! is_dir ( DATA_DIR )){
    is_dirName ( DATA_DIR );
}

// 日记目录
if( ! is_dir(DATA_DIR . 'Log')){
    is_dirName ( DATA_DIR . 'Log' );
}

//创建编译文件夹
if(! is_dir(DATA_DIR.'Compiled')){
    is_dirName(DATA_DIR.'Compiled');
}

//创建缓存文件夹
if(! is_dir(DATA_DIR.'Cache')){
    is_dirName(DATA_DIR.'Cache');
}

if(file_exists (BIN .'Log'.IS_EXP)) {
    require BIN . 'Log' .IS_EXP;
}else{
    is_exit( lang('loadDataLogError') );
}

if(file_exists(BIN . 'Loader' . IS_EXP)){
    require BIN . 'Loader' . IS_EXP;
}else{
    is_exit( lang('loadLoader') );
}

if(file_exists(BIN.'Route'.IS_EXP)){
    require BIN .'Route'.IS_EXP;
}else{
    is_exit(lang('loadRoute'));
}
