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

class TestDataAction extends \Upadd\Frame\Action
{


    public function test()
    {
        return 'hi, welcome to use Upadd';
    }

    public function testPath()
    {
        return host();
    }

    public function getJson()
    {
        $json = Data::json();
        return $this->msg(200, 'ok',$json);
    }


    public function stream()
    {
        $json = Data::stream();
        return $this->msg(200, 'ok',json($json));
    }


    public function xml()
    {
        $this->setResponseType('xml');
        return $this->msg(200, 'ok', [1, 2, 23, 23232, 32, 32, 32, 32, 32, 3]);
    }


}