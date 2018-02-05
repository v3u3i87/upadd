<?php

namespace console\action;

//use console\swoole\TestHtppServer;
use Config;
use console\swoole\TestServer;
use Upadd\Swoole\HttpServer;
use Upadd\Bin\Client\Http;


class TestAction extends \Upadd\Frame\Action
{

    public $client = null;


    public function zmq(){
        $client = Http::create('http://zmq.cc');
        $client->asyncHttp();
    }


    /**
     * php console.php --u=test --p=tcp
     */
    public function tcp()
    {
        echo 'tcp';
        echo "\n";
        $tcp = Config::get('swoole@tcp');
        print_r($tcp);
        $test = new TestServer($tcp['name'], $tcp['host']);
        $test->start();
    }

    //php console.php --u=test --p=http
    public function http()
    {
        $swooleHtpp = Config::get('swoole@http');
        return HttpServer::create($swooleHtpp['name'], $swooleHtpp['host'])->start();
    }

    public function a()
    {
       echo  json(['zmq'=>'zhang mao qiang','test'=>123,'Info'=>'to upadd']);
    }

    //php console.php --u=test --p=info
    public function info()
    {
        /**
         * int 400000  in 大于2M数据,无法传送
         */
        $int = 1000;
//        unlink('tcp.log');
        //看看生产文件多大
        $this->client = new \swoole_client(SWOOLE_SOCK_TCP);
        if (!$this->client->connect("127.0.0.1", 9090, -1)) {
            echo "Error: {$this->client->errMsg}[{$this->client->errCode}]\n";
        }
//        $this->client->send(str_repeat('-zmq-', $int));
        $this->client->send(verificationCode(4));
        $this->client->send("\r\n\r\n");
        $message = $this->client->recv();
        echo $message;
        echo "\n";
        if ($message) {
            $this->client->close();
            echo "\r\n";
            $endtime = (microtime(true)) - RUNTIME;
            echo $endtime;
            echo "\r\n";
            exit('--exit the program--' . "\r\n");
        } else {
            echo "等待返回 \r\n";
        }

    }


}
