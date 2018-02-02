<?php

namespace example\Logic\Test;

use Di;

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 2018/1/28
 * Time: 下午4:49
 * Name:
 */
class Info
{

    public $s = [];


    public static function __init()
    {
        return new static();
    }


    public function demo()
    {
        p(Di::getAll());
        $req = Di::get('Request');
        p($req->server);
    }


}