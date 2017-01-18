<?php
namespace console\bin;

use Config;

use Upadd\Swoole\TcpServer;

/**
 * 推送服务
 * @Cli php console.php --u=test --p=main
 * @package console\swoole\server
 */
class TestServer extends TcpServer
{

    /**
     * 初始化操作
     * @param $server
     */
    protected function initServer($server){}

    /**
     * 加载新代码
     * @param $server
     * @param $worker_id
     */
    protected function initTask($server, $worker_id){}

    /**
     * @param array $param
     * @param array $client
     * @return array
     */
    protected function doWork($param=[],$client=[])
    {
        echo "-----------------\r\n";
        print_r([$param,$client]);
        echo "================end=============\r\n";
        return $this->toFinish($param['fd'],json($param));
    }


}