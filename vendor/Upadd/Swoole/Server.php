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

use swoole_server;
use swoole_http_server;

use Config;
use Upadd\Bin\UpaddException;
use Upadd\Swoole\Lib\Help;


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

    protected $server = null;

    protected $config = [];

    protected $type = null;

    protected $host = '127.0.0.1';

    protected $port = '9988';

    protected $name = 'upadd';

    protected $pid = '';

    protected $pidFile = '';


    /**
     * Server constructor.
     * @param $name
     * @param null $address
     */
    public function __construct($name, $address = null)
    {
        $this->name($name);
        if (null === $address) {
            $address = 'tcp://' . $this->host . ':' . $this->port;
        }
        $addressParam = Help::parseAddress($address);
        $this->type = $addressParam['sock'];
        $this->host = $addressParam['host'];
        $this->port = $addressParam['port'];
        $this->config = array_merge($this->config, (array)$this->configure());
    }

    /**
     * @param $name
     * @return $this;
     */
    public function name($name)
    {
        $this->name = $name;

        $this->pid = '/tmp/' . str_replace(' ', '-', $this->name) . '.pid';

        return $this;
    }

    /**
     * 如果需要自定义自己的swoole服务器,重写此方法
     * @return swoole_server
     */
    public function initSwoole()
    {
        return new swoole_server($this->host, $this->port);
    }


    protected function handleCallback()
    {
        $handles = get_class_methods($this);
        $isListenerPort = false;
        foreach ($handles as $value) {
            if ('on' == substr($value, 0, 2)) {
                if ($isListenerPort) {
                    if (in_array($value, ['onConnect', 'onClose', 'onReceive', 'onPacket', 'onReceive'])) {
                        $this->server->on(lcfirst(substr($value, 2)), [$this, $value]);
                    }
                } else {
                    $this->server->on(lcfirst(substr($value, 2)), [$this, $value]);
                }
            }
        }
        return $this;
    }

    /**
     * 引导服务，当启动是接收到 swoole server 信息，则默认以这个swoole 服务进行引导
     * @param swoole_server|swoole_server_port $swoole
     * @return $this
     */
    public function bootstrap($swoole = null)
    {
        $this->server = null === $swoole ? $this->initSwoole() : $swoole;
        $this->server->set($this->config);
        $this->handleCallback();
        return $this;
    }


    public function start()
    {
        try {
            $this->bootstrap();
//            // 多端口监听
//            foreach ($this->listens as $listen) {
//                $swoole = $this->server->listen($listen->getHost(), $listen->getPort(), $this->swoole->type);
//                $listen->bootstrap($swoole);
//            }
//            // 进程控制
//            foreach ($this->processes as $process) {
//                $this->server->addProcess($process->getProcess());
//            }
            $this->server->start();
        } catch (UpaddException $e) {
            print_r($e->getMessage());
        }
    }


    /**
     *
     * @return mixed
     */
    abstract public function configure();

    /**
     * 启动服务
     * @param \swoole_server $serv
     */
    public function onStart(swoole_server $serv)
    {
        swoole_set_process_name($this->name . " Master");
        echo "Start\n";
    }

    public function onManagerStart(swoole_server $serv)
    {
        swoole_set_process_name($this->name . " Manager");
    }

    public function onManagerStop(swoole_server $serv)
    {
        echo "Manager Stop , shutdown server\n";
        $serv->shutdown();
    }

    //worker and task init
    public function onWorkerStart(swoole_server $server, $worker_id)
    {
        $istask = $server->taskworker;
        if (!$istask) {
            //worker
            swoole_set_process_name($this->name . " Worker {$worker_id}");
        } else {
            //task
            swoole_set_process_name($this->name . " Task {$worker_id}");
//            $this->initTask($server, $worker_id);
        }
    }

    public function onWorkerError(swoole_server $serv, $worker_id, $worker_pid, $exit_code)
    {
        //using the swoole error log output the error this will output to the swtmp log
        var_dump($this->name . " Worker Error", array($serv, $worker_id, $worker_pid, $exit_code));
    }

    /**
     * @param swoole_server $server
     * @param int $worker_id
     * @return void
     */
    public function onWorkerStop(swoole_server $server, $worker_id)
    {
        var_dump(sprintf('Server <info>%s</info> Worker[<info>#%s</info>] is shutdown', $this->name, $worker_id));
    }

}