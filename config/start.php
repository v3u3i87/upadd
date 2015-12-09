<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/4/10
 * Time: 17:05
 */
return array(

    'environment'=>array(
        'local'=>array('RR-ZMQ','demo','Mac-zmq.local'),
        'dev'=>array('renrentest','demo')
    ),

    'is_autoload'=>false,

    //命名空间辐射关系
    'autoload'=>array(
        //控制器
        "Up\\Action\\"=>'works/action/',
        "Up\\Logic\\"=>'works/logic/',

    ),

    //CLI模式下命名空间
    'cli_action_autoload'=>'works\\action\\'










);