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

abstract class Server{


    /**
     * 原始对象
     * @var null
     */
    protected $_obj = null;

    /**
     * tcp 服务对象
     * @var null|\swoole_server
     */
    protected $tcpServer = null;

    /**
     * http 服务对象
     * @var null
     */
    protected $httpServer = null;

    /**
     * tcp 配置参数
     * @var array
     */
    protected $tcpConfig = [];

    /**
     * http 配置参数
     * @var array
     */
    protected $httpConfig = [];

    /**
     * 判断是否启动TCP
     * @var null
     */
    protected $is_tcp = null;

    /**
     * 判断是否启动HTTP
     * @var null
     */
    protected $is_http = null;


    /**
     * Server constructor.
     * @param string $ip
     * @param int $port
     */
    public function __construct($ip='0.0.0.0',$port=9988)
    {
        $this->_obj = new \swoole_server($ip, $port);
        $this->_obj->on('Start', array($this, 'onStart'));
        $this->_obj->on('Receive', array($this, 'onReceive'));
        $this->_obj->on('Task', array($this, 'onTask'));
        $this->_obj->on('Finish', array($this, 'onFinish'));
        $this->initServer($this->_obj);
    }

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


    abstract protected function initServer($server);

    abstract protected function initTask($server, $worker_id);

    /**
     * 具体业务逻辑代码
     * 回调思路实现
     * @param $param
     * @return mixed
     */
    abstract protected function doWork($param=[],$client=[]);

}