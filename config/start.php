<?php

return array(

    'environment'=>array(
        'local'=>array('RR-ZMQ','demo','Mac-zmq.local','Mac-zmq.lan','PC-PC'),
        'dev'=>array('xcvu')
    ),

    'is_autoload'=>false,

    //命名空间辐射关系
    'autoload'=>array(
        //控制器
        "Up\\Action\\"=>'works/action/',
        "Up\\Logic\\"=>'works/logic/',

    ),

    //CLI模式下命名空间
    'cli_action_autoload'=>'console\\action\\',

    /**
     * 开启全局别名
     */
    'is_alias'=>false,

    /**
     * 自定义设置别名
     */
    'alias'=>array('main'=>'extend\admin\Main'),

    /**
     * 排除配置文件以外的定义文件
     */
    'exclude_config'=>['routing','filters','extend'],

    /**
     * 是否开启
     */
    'is_session'=>IS_SESSION,

    /**
     * 控制器
     */
    'set_action'=>'u',
    /**
     * 方法
     */
    'set_function'=>'p',

    /**
     * 设置session系列
     */
    'session'=>[
        'domain'=>false,
        'expire'=>false,
        'use_cookies'=>false,
        'cache_limiter'=>false,
        'cache_expire'=>false,
    ],

);