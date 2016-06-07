<?php

return array(

    'environment'=>array(
        'local'=>array('RR-ZMQ','demo','Mac-zmq.local'),
        'dev'=>array('renrentest','demo'),
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
     * 控制器
     */
    'set_action'=>'u',
    /**
     * 方法
     */
    'set_function'=>'p',

);