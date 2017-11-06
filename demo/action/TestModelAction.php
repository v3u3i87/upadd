<?php
/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 16/4/25
 * Time: 19:36
 * Name:.
 */

namespace demo\action;

use Data;
use example\model\InfoModel;

class TestModelAction extends \Upadd\Frame\Action
{

    public function add()
    {
        InfoModel::add([
            'name'=>mt_rand(1111,3333),
            'code'=>verificationCode(4),
            'add_time'=>time(),
        ]);
        return InfoModel::sort('id')->page()->get();
    }



}