<?php
/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 16/11/8
 * Time: 18:10
 * Name:
 */
namespace Upadd\Swoole;

use Config;
use swoole\Server as swooleServer;

abstract class Server{

    private $_obj;

    private $tcpConfig = [];

    /**
     * Server constructor.
     * @param string $ip
     * @param int $port
     */
    public function __construct($ip='0.0.0.0',$port=9988)
    {
        $this->_obj = new swooleServer($ip, $port);
        $this->getTcpConfig();
        echo "启动吧".gethostname().'端口:'.$port;
        echo "\r\n";
        $this->_obj->on('Start', array($this, 'onStart'));
//        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->_obj->on('Receive', array($this, 'onReceive'));
//        $this->serv->on('Close', array($this, 'onClose'));
        $this->_obj->on('Task', array($this, 'onTask'));
        $this->_obj->on('Finish', array($this, 'onFinish'));

        $this->initServer($this->_obj);
    }

    /**
     * 获取TCP配置
     */
    private function getTcpConfig()
    {
        $this->tcpConfig = [

            'worker_num' => 2,
            'max_request'=>10000,
            'log_file'=>host().'data/console/swoole.logs',
            'task_tmpdir'=>host().'data/console/task/',
//            'debug_mode'=> 1,
            'daemonize' => Config::get('tag@swoole_email_daemonize'),
            'task_worker_num' =>4,
            'dispatch_mode'=>3,


            //收发问题
            'open_eof_check'=>true,
            'open_eof_split' => true,
            //关闭Nagle合并算法
            'open_tcp_nodelay'     =>  true,
            'package_length_type' => 'N',
            'package_length_offset' => 0,
            'package_body_offset' => 0,

            //最大包长度
            'package_max_length'=>2097152,
            'buffer_output_size' => 3145728, //1024 * 1024 * 3,
            'pipe_buffer_size' => 33554432, // 1024 * 1024 * 32,
            'package_eof'=>"\r\n\r\n",
            'backlog'=>3000,
        ];
    }


    abstract protected function initServer($server);

    abstract protected function initTask($server, $worker_id);

    /**
     * 具体业务逻辑代码
     * 回调思路实现
     * @param $param
     * @return mixed
     */
    abstract protected function doWork($param=[],$client=[]);

    /**
     * 完成
     * @param $fd
     * @param $results
     * @return array
     */
    protected function toFinish($fd,$results)
    {
        return [
            'fd'=>$fd,
            'results'=>$results
        ];
    }

    /**
     * 启动服务
     */
    public function start()
    {
        $this->_obj->set($this->tcpConfig);
        $this->_obj->start();
    }

    /**
     * 启动服务
     * @param \swoole_server $serv
     */
    final public function onStart($serv)
    {
//        swoole_set_process_name("master");
        echo "Start\n";
    }

    /**
     * 连接对象发送数据
     * @param $serv
     * @param $fd
     * @param $from_id
     */
    final public function onConnect($serv, $fd, $from_id){}


    /**
     * 响应客户端
     * @param \swoole_server $serv
     * @param $fd 数据ID
     * @param $from_id
     * @param $data
     */
    final public function onReceive($serv, $fd, $from_id, $data)
    {
        $serv->task(['fd'=>$fd,'from_id'=>$from_id,'results'=>$data]);
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
    final public function onTask($serv,$task_id,$from_id, $data)
    {
        echo "This Task {$task_id} from Worker {$from_id}\n";
        return $this->doWork($data,['connection_info'=>$serv->connection_info($data['fd'])]);
    }

    /**
     * 返回数据到客户端
     * @param $serv
     * @param $task_id
     * @param $data
     * @return bool
     */
    final public function onFinish($serv,$task_id, $data)
    {
        $fd = $data['fd'];
        $results = $data['results'];
        $serv->send($fd,$results);
        return true;
    }

    final public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection\n";
    }

}