<?php

namespace console\action;

//use console\swoole\TestHtppServer;
use Config;
use console\swoole\TestServer;
use Upadd\Swoole\HttpServer;

class TestAction extends \Upadd\Frame\Action
{

    public $client = null;

    /**
     * php console.php --u=test --p=tcp
     */
    public function tcp()
    {
        $tcp = Config::get('swoole@tcp');
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
        $int = 400000;
        unlink('b.log');
        //看看生产文件多大
        file_put_contents('b.log', str_repeat('-zmq-', $int));
//        exit;
        for ($i = 0; $i < 100; $i++) {
            $this->client = new \swoole_client(SWOOLE_SOCK_TCP);
            if (!$this->client->connect("127.0.0.1", 9988, -1)) {
                echo "Error: {$this->client->errMsg}[{$this->client->errCode}]\n";
            }
            $this->client->send(str_repeat('-zmq-', $int));
            $this->client->send("\r\n\r\n");
            $message = $this->client->recv();
            echo $message;
            echo "\n";
            if ($message) {
                $this->client->close();
            } else {
                $this->client->close();
                return false;
            }
        }

        echo "\r\n";
        $endtime = (microtime(true)) - RUNTIME;
        echo $endtime;
        echo "\r\n";
        exit('--exit the program--' . "\r\n");

    }


}
