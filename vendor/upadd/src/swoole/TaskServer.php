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
use swoole_server;
use Upadd\Bin\UpaddException;

abstract class TaskServer extends TcpServer
{

    /**
     * 转发任务
     * @param $serv
     * @param $task_id
     * @param $from_id
     * @param $data
     * @return mixed
     */
    public function onTask(swoole_server $_server, $task_id, $from_id, $data)
    {
        return $this->doWork($data, ['connection_info' => $_server->connection_info($data['fd'])]);
    }


    /**
     * 返回客户端
     * @param $serv
     * @param $task_id
     * @param $data
     * @return bool
     */
    public function onFinish(swoole_server $_server, $task_id, $data)
    {
        return $_server->send($data['fd'], $data['results']);
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


    /**
     * 具体业务逻辑代码
     * 回调思路实现
     * @param $param
     * @return mixed
     */
    abstract protected function doWork($param = [], $client = []);


}