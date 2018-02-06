<?php
namespace console\swoole;

use Config;

use Upadd\Swoole\TaskServer;
use Upadd\Swoole\TcpServer;


/**
 * 测试
 * @Cli php console.php --u=test --p=main
 * @package console\swoole\server
 */
class TestServer extends TaskServer
{

    /**
     * @param array $param
     * @param array $client
     * @return array
     */
    public function doWork($param=[],$client=[])
    {
        return $this->task($param,$client);
    }


    private function tcp($param=[],$client=[])
    {
        return json([$param,$client]);
    }

    private function task($param=[],$client=[]){
        echo "-----------------\r\n";
        print_r([$param,$client]);
        echo "================end=============\r\n";
        return $this->results($param['fd'],json($client));
    }



}