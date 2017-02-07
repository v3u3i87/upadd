<?php
namespace console\swoole;

use Config;

use Upadd\Swoole\HttpServer;

/**
 * 测试
 * @Cli php console.php --u=test --p=http
 * @package console\swoole\server
 */
class TestHtppServer extends HttpServer
{

    /**
     * @param array $param
     * @param array $client
     * @return array
     */
    protected function doWork($param=[],$client=[])
    {
        return $this->toFinish($param['fd'],json($param));
    }


}