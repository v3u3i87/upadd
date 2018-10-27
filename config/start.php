<?php

return array(

    'environment' => array(
        'local' => ['you name in a', 'you name in b'],
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
    'alias' => [
//        'Di'=>'Upadd\Bin\Package\Di',
    ],

    /*
     * 排除配置文件以外的定义文件
     */
    'exclude_config' => ['routing', 'filters', 'extend'],

    /*
     * 是否开启 session
     */
    'is_session' => false,

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
     * 设置日志文件最大容量
     * 1G=1099511627776
     * 10M=10485760
     */
    'log_maximum' => 10485760,


);
