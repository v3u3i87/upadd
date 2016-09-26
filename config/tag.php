<?php
/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 15/12/22
 * Time: 16:55
 * Name:.
 */

return array(

    /*
     * 生产环境
     */
    'debug'=>false,

    /*
     * 是否开启前端域名
     */
    'is_front_domain' => false,

    /*
     * 前端域名
     */
    'front_domain' => 'http://upadd.cn',

    /**
     * 设置redis
     */
    'redis'=>[
        'host'=>'127.0.0.1',
        'port'=>'6379',
    ],

    /**
     * 设置memcache
     */
    'memcache'=>[
        'host' => 'localhost',
        'port' => 11211,
        'timeout' => 3, // 连接超时时间
        'compression' => true, // 默认是否压缩的标志
        'lifetime' => 3600  // 默认缓存时间
    ],



    /**
     * true 进程服务
     * false = 会话模式
     */
    'swoole_email_daemonize'=>false,


 );
