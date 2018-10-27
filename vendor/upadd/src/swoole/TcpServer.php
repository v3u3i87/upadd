<?php

namespace Upadd\Swoole;

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 2017/1/18
 * Time: 下午8:51
 * Name:
 */

use Config;
use Swoole\Server as swoole_server;
use Upadd\Bin\UpaddException;

abstract class TcpServer extends Server
{
    /**
     * 配置文件
     * @return mixed
     */
    public function configure()
    {
        $config = Config::get('swoole@tcp_param');
        $config['daemonize'] = Config::get('swoole@daemonize');
        return $config;
    }


    /**
     * 具体业务逻辑代码
     * 回调思路实现
     * @param $param
     * @return mixed
     */
    abstract protected function doWork($param, $client = []);


    /**
     * 响应客户端
     * @param $serv swoole_server对象
     * @param $fd TCP客户端连接的文件描述符
     * @param $from_id TCP连接所在的Reactor线程ID
     * @param $data 收到的数据内容，可能是文本或者二进制内容
     * @return bool
     */
    public function onReceive(swoole_server $_server, $fd, $from_id, $data)
    {
        return $this->doWork(
            ['fd' => $fd, 'from_id' => $from_id, 'results' => $data],
            //客户端信息
            ['connection_info' => $_server->connection_info($fd)]
        );
    }

    /**
     * 完成
     * @param $fd
     * @param $results
     * @return array
     */
    protected function results($fd, $results)
    {
        return [
            'fd' => $fd,
            'results' => $results
        ];
    }


}