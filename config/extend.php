<?php

if(APP_DEBUG) {
    //引入报错文件
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

//测试用咧
//$app->setWorkModule(array(
//    'Info'=>new \works\logic\Info,
//));

