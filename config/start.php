<?php

return array(

    'environment' => array(
        'local' => array('RR-ZMQ', 'demo', 'Mac-zmq.local', 'Mac-zmq.lan','localhost'),
    ),

    /**
     * 判断是否自定义加载
     */
    'is_autoload' => false,

    //CLI模式下命名空间
    'cli_action_autoload' => 'console\\action\\',

    /*
     * 开启全局别名
     */
    'is_alias' => false,

    /*
     * 自定义设置别名
     */
    'alias' => [],

    /*
     * 排除配置文件以外的定义文件
     */
    'exclude_config' => ['routing', 'filters', 'extend'],

    /*
     * 是否开启
     */
    'is_session' => IS_SESSION,

    /*
     * 控制器
     */
    'set_action' => 'u',
    /*
     * 方法
     */
    'set_function' => 'p',

    /*
     * 设置session系列
     */
    'session' => [
        'domain' => false,
        'expire' => false,
        'use_cookies' => false,
        'cache_limiter' => false,
        'cache_expire' => false,
    ],

    /**
     * 是否使用
     */
    'is_use_redis' => false,

    /**
     * 是否开启
     */
    'is_use_memcache' => false,


    /**
     * true = 开启
     * false = 关闭
     */
    'is_swoole'=>true,



    /**
     * 是否开启URL错误跳转
     * true = 开启
     * false = 关闭
     */
    'is_http_url_error'=>false,


    /**
     *  URL错误跳转
     */
    'http_url_error'=>'/',


);
