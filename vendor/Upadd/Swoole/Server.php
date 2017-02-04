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

abstract class Server
{


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
    public function __construct($ip = '0.0.0.0', $port = 9988, $httpPort = 8080)
    {
        $mode = Config::get('swoole@is_mode');
        if ($mode == 1) {
            $this->_obj = new \swoole_server($ip, $port);
            $this->tcpServer = $this->_obj;
            $this->tcpServer->on('Receive', array($this, 'onReceive'));
        } elseif ($mode == 2) {
            $this->_obj = new \swoole_http_server($ip, $httpPort);
            $this->httpServer = $this->_obj;
            $this->_obj->on('Request', array($this, 'onRequest'));
        } elseif ($mode == 3) {
            $this->_obj = new \swoole_http_server($ip, $httpPort);
            $this->_obj->on('Request', array($this, 'onRequest'));
            $this->tcpServer = $this->_obj->addListener($ip, $port, \SWOOLE_TCP);
            $this->tcpServer->on('Receive', array($this, 'onReceive'));
        }

        $this->_obj->on('Start', array($this, 'onStart'));
        $this->_obj->on('ManagerStart', array($this, 'onManagerStart'));
        $this->_obj->on('ManagerStop', array($this, 'onManagerStop'));
        $this->_obj->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->_obj->on('WorkerError', array($this, 'onWorkerError'));
        $this->_obj->on('Task', array($this, 'onTask'));
        $this->_obj->on('Finish', array($this, 'onFinish'));
        $this->initServer($this->_obj);
    }

    /**
     * 启动服务
     * @param \swoole_server $serv
     */
    final public function onStart(\swoole_server $serv)
    {
        swoole_set_process_name("upadd: master");
        echo "Start\n";
    }

    //application server first start
    final public function onManagerStart(\swoole_server $serv)
    {
        swoole_set_process_name("upadd: manager");
    }

    final public function onManagerStop(\swoole_server $serv)
    {
        echo "Manager Stop , shutdown server\n";
        $serv->shutdown();
    }

    //worker and task init
    final public function onWorkerStart($server, $worker_id)
    {
        $istask = $server->taskworker;
        if (!$istask) {
            //worker
            swoole_set_process_name("upadd: worker {$worker_id}");
        } else {
            //task
            swoole_set_process_name("upadd: task {$worker_id}");
            $this->initTask($server, $worker_id);
        }

    }

    final public function onWorkerError(\swoole_server $serv, $worker_id, $worker_pid, $exit_code)
    {
        //using the swoole error log output the error this will output to the swtmp log
        var_dump("workererror", array($serv, $worker_id, $worker_pid, $exit_code));
    }

    /**
     * 响应HTTP请求
     * @param \swoole_http_request $request
     * @param \swoole_http_response $response
     */
    final public function onRequest(\swoole_http_request $request, \swoole_http_response $response)
    {
        return $this->response($request,$response);
    }

    /**
     * http 响应处理
     * @param $request
     * @param $response
     * @return mixed
     */
    abstract protected function response($request,$response);


    /**
     * 连接对象发送数据
     * @param $serv
     * @param $fd
     * @param $from_id
     */
    final public function onConnect($serv, $fd, $from_id)
    {
    }


    /**
     * 响应客户端
     * @param \swoole_server $serv
     * @param $fd 数据ID
     * @param $from_id
     * @param $data
     */
    final public function onReceive($serv, $fd, $from_id, $data)
    {
        $serv->task(['fd' => $fd, 'from_id' => $from_id, 'results' => $data]);
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
    final public function onTask($serv, $task_id, $from_id, $data)
    {
        echo "This Task {$task_id} from Worker {$from_id}\n";
        return $this->doWork($data, ['connection_info' => $serv->connection_info($data['fd'])]);
    }

    /**
     * 返回数据到客户端
     * @param $serv
     * @param $task_id
     * @param $data
     * @return bool
     */
    final public function onFinish($serv, $task_id, $data)
    {
        $fd = $data['fd'];
        $results = $data['results'];
        $serv->send($fd, $results);
        return true;
    }

    final public function onClose($serv, $fd, $from_id)
    {
        echo "Client {$fd} close connection\n";
    }


    /**
     * 完成
     * @param $fd
     * @param $results
     * @return array
     */
    protected function toFinish($fd, $results)
    {
        return [
            'fd' => $fd,
            'results' => $results
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
    abstract protected function doWork($param = [], $client = []);

}