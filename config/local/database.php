<?php


return array(

    'db' => array(
        'type' => 'pdo_mysql',
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '123456',
        'name' => 'demo',
        'port' => 3306,
        'charset' => 'UTF8',
        'prefix' => 'up_',
    ),


    /**
     * 单库为 false
     */
    'many'=>false,

);
