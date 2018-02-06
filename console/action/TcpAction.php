<?php

namespace console\action;

use Config;
use console\swoole\TestServer;
use Upadd\Swoole\ClientConnect\AsyncTcp;
use Upadd\Swoole\ClientConnect\SyncTcp;

class TcpAction extends \Upadd\Frame\Action
{

    public $client = null;


    /**
     * php console.php --u=tcp --p=create
     */
    public function create()
    {
        echo 'tcp';
        echo "\n";
        $tcp = Config::get('swoole@tcp');
        print_r($tcp);
        $test = new TestServer($tcp['name'], $tcp['host']);
        $test->start();
    }

    /**
     * php console.php --u=tcp --p=async
     */
    public function async()
    {
        $async = AsyncTcp::create('tcp://127.0.0.1:9090');
        $async->connect();
        $async->send(str_repeat('-zmq-', 100));
        $async->send("\r\n\r\n");
    }

    /**
     * php console.php --u=tcp --p=sync
     */
    public function sync()
    {
        $sync = SyncTcp::create('tcp://127.0.0.1:9090');
        $sync->connect();
        $sync->send(str_repeat('-zmq-', 100));
        $sync->send("\r\n\r\n");
        print_r($sync->getResponse());
    }


}
