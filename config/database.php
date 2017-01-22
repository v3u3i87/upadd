<?php

return array(

    'db' => [
        'type' => 'pdo_mysql',
        'host' => '127.0.0.1',
        'user' => 'root',
        'pass' => 'root',
        'name' => 'demo',
        'port' => 3306,
        'charset' => 'UTF8',
        'prefix' => 'up_',
    ],


    /**
     * 开启单库：false
     * 开启多库：true
     */
    'many'=>false,

);
