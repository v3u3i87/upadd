<?php

namespace works\action;

use works\logic\Info;
use works\action\NameAction;
use works\model\InfoMo;
use Config;
use Session;

class IndexAction extends BaseAction{

    public function home(){
        $info = new InfoMo();

//        p($_SESSION);
//        Session::del();
//        exit;

          //设置参数
//        Session::set('info',array(2,3,4,6));

          //递增数据
//        Session::add('info',$info->abc());

        //获取数据
//      $info = Session::get('info');

        //断点打印
//        p($info);

    }

    public function abc(){
        p(Session::get('info'));
    }


    public function info(){
        echo 'info';
    }




}