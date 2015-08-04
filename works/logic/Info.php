<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/8/4
 * Time: 11:12
 */

namespace works\logic;

use works\logic\Name;
use works\model\UserMo;

class Info{

    static function in(){
        echo 11111;
        echo '<br />';
        Name::haha();
        echo '<br />';
        UserMo::info();
    }


}