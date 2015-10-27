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

class IndexAction extends BaseAction{


    public function home(){
        $this->_view->path('aa.html');
    }

    public function abc(){
        $this->_view->path('aaa.html');
    }




}