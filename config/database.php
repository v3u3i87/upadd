<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/4/10
 * Time: 17:35.
 */

return array(

    'db' => [
        'type' => 'pdo_mysql',
        'host' => 'api.upadd.cn',
        'user' => 'root',
        'pass' => 'root',
        'name' => 'test',
        'port' => 3306,
        'charset' => 'UTF8',
        'prefix' => 'up_',
    ],


    /**
     * 单库为 false
     */
    'many'=>false,

);
