<?php

namespace works\action;

use works\logic\Info;
use works\action\NameAction;
//use Upadd\Bin\Config\Config;
use works\model\InfoMo;
use Config;

class IndexAction extends BaseAction{


    public function home(){
      echo 'hello upadd.';
    }

    public function abc(){
       echo 'hi.abc';
    }


    public function info(){
        echo 'info';
    }




}