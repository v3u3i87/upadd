<?php

namespace console\action;

use console\swoole\TestHtppServer;
use console\swoole\TestServer;

class TestAction extends \Upadd\Frame\Action
{

    public $client = null;

    public function tcp()
    {
        $test = new TestServer();
        return $test->start();
    }


    public function http(){
        $test = new TestHtppServer('0.0.0.0',9988,8080);
        return $test->start();
    }


    public function info()
    {
        $this->client = new \swoole_client(SWOOLE_SOCK_TCP);
        if( !$this->client->connect("127.0.0.1", 9988,-1) )
        {
            echo "Error: {$this->client->errMsg}[{$this->client->errCode}]\n";
        }

        $this->client->send("wwewewewe");
        $this->client->send("\r\n\r\n");
        $message = $this->client->recv();
        print_r($message);
        if($message){
            $this->client->close();
            return true;
        }else{
            $this->client->close();
            return false;
        }

    }



}
