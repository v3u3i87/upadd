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

class HomeAction extends \Upadd\Frame\Action
{


    public function test()
    {
        return 'hi, welcome to use Upadd';
    }

    public function testPath(){
        return host();
    }
}
