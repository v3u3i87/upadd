<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/7/14
 * Time: 16:39
 */

namespace works\action;

use works\logic\Info;
use works\action\NameAction;
use Upadd\Bin\Config\Config;


class IndexAction extends BaseAction{


    public function home(){
        echo 'home';
    }


    public function abc(){
       echo 'hi.abc';
    }


    public function info(){
        echo 'home.info';
    }




}