<?php
namespace works\action;

use Config;
use Session;
use Data;


class DemoAction extends BaseAction{

    public function home(){
        $name = Data::get('name',false);
        $zmq = Data::get('zmq');
        /**
         * 获取所有的请求数据get ,post
         */
        $all = Data::all();

        $this->val('name','测试,121212');
        $this->val('info',array(1,2,3,5,6));
        $this->view('demo.html');
    }

    /**
     * session 使用Demo
     */
    public function sessionDemo(){
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


    public function info(){
        echo 'info';
    }

    public function name(){
        echo 'name';
    }




}