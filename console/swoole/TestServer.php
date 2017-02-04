<?php
namespace console\swoole;

use Config;

use Upadd\Swoole\TaskServer;

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
        echo "-----------------\r\n";
        print_r([$param,$client]);
        echo "================end=============\r\n";
        return $this->results($param['fd'],json($param));
    }


}