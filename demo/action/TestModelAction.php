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
            'code'=>1,
        ]);
        return InfoModel::sort('id')->limit('0,10')->get();
    }



}