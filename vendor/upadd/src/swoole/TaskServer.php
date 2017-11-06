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


abstract class TaskServer extends TcpServer
{

    /**
     * 配置文件
     * @return mixed
     */
    public function configure()
    {
        $config = Config::get('swoole@tcp_task_param');
        $config['daemonize'] = Config::get('swoole@daemonize');
        return $config;
    }

    /**
     * 连接对象发送数据
     * @param $serv
     * @param $fd
     * @param $from_id
     */
    public function onConnect(swoole_server $_server, $fd, $from_id){}

    public function onClose(swoole_server $_server, $fd, $from_id){}


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
        $_server->task(['fd' => $fd, 'from_id' => $from_id, 'results' => $data]);
        return true;
    }


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




}