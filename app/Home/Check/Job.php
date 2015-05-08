<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/4/20
 * Time: 16:06
*/
namespace App\Home\Check;

use Upadd\Frame\Check;

class Job extends Check{

    public function add($name){
        if(!$name['a']){
            $this->_message = '不能为空';
            $this->_flag = false;
        }

        return $this->_flag;
    }




}

