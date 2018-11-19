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

use Swoole\Server as swoole_server;
use Swoole\Http\Server as swoole_http_server;
use Config;
use Upadd\Swoole\Lib\Help;
use Upadd\Bin\UpaddException;
use Log;

abstract class Server
{

    /**
     * swoole server对象
     * @var null
     */
    protected $server = null;

    /**
     * 配置文件
     * @var array
     */
    protected $config = [];

    /**
     * 服务类型协议
     * @var null
     */
    protected $type = null;

    /**
     * 地址
     * @var string
     */
    protected $host = '0.0.0.0';

    /**
     * 端口
     * @var string
     */
    protected $port = '6688';

    /**
     * 进程名称
     * @var string
     */
    protected $name = 'upadd';

    /**
     * 进程pid号
     * @var string
     */
    protected $pid = '';

    /**
     * 进程PID号文件
     * @var string
     */
    protected $pidFile = '';

    private $serviceType = '';

    /**
     * @var array
     */
    protected $localInfo = [];

    /**
     * Server constructor.
     * @param $name
     * @param null $address
     */
    public function __construct($name, $address = null)
    {
        if (!extension_loaded('swoole')) {
            throw new UpaddException('Load swoole extension failure!');
        }

        $this->name($name);
        if (null === $address) {
            $address = 'tcp://' . $this->host . ':' . $this->port;
        }
        $addressParam = Help::parseAddress($address);
        if (isset($addressParam['scheme'])) {
            $this->serviceType = strtoupper($addressParam['scheme']);
        }

        $this->type = $addressParam['sock'];
        $this->host = $addressParam['host'];
        $this->port = $addressParam['port'];
        $this->config = array_merge($this->config, (array)$this->configure());
        //设置本地信息
        $this->localInfo = [
            'serviceType' => $this->serviceType,
            'host' => $this->host,
            'port' => $this->port,
            'config' => $this->config
        ];
    }


    /**
     *
     * @return mixed
     */
    abstract public function configure();

    /**
     * @return mixed
     */
    protected function doListen()
    {
    }


    /**
     * 启动服务
     * @param \swoole_server $_server
     */
    public function onStart(swoole_server $_server)
    {
        swoole_set_process_name($this->name . " Master");
	Log::cmd("pid:{$this->pid} {$this->name} Start type:{$this->type} ip:{$this->host} port:{$this->port} ");
        $this->localInfo['pid'] = $this->pid;
        $this->localInfo['managerNmae'] = $this->name;
        p($this->localInfo, true);
        echo "Start\n";
    }

    public function onManagerStart(swoole_server $_server)
    {
        swoole_set_process_name($this->name . " Manager");
    }

    public function onManagerStop(swoole_server $_server)
    {
        Log::cmd("Manager Stop , shutdown server");
        $_server->shutdown();
    }

    //worker and task init
    public function onWorkerStart(swoole_server $_server, $worker_id)
    {
        $istask = $_server->taskworker;
        if (!$istask) {
            //worker
            swoole_set_process_name($this->name . " Worker {$worker_id}");
        } else {
            //task
            swoole_set_process_name($this->name . " Task {$worker_id}");
        }
    }

    public function onWorkerError(swoole_server $_server, $worker_id, $worker_pid, $exit_code)
    {
        //array($_server, $worker_id, $worker_pid, $exit_code)
        Log::cmd($this->name . " Worker Error");
    }

    /**
     * @param swoole_server $server
     * @param int $worker_id
     * @return void
     */
    public function onWorkerStop(swoole_server $_server, $worker_id)
    {
        Log::cmd(sprintf('Server %s Worker[ #%s ] is shutdown', $this->name, $worker_id));
    }


    /**
     * 对外创建
     * @param $name
     * @param $address
     * @return static
     */
    public static function create($name, $address)
    {
        return new static($name, $address);
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
    public function initServer()
    {
        if ($this->serviceType === 'UDP') {
            return new swoole_server($this->host, $this->port, SWOOLE_PROCESS, SWOOLE_SOCK_UDP);
        } else {
            return new swoole_server($this->host, $this->port, SWOOLE_PROCESS, SWOOLE_SOCK_TCP);
        }
    }


    protected function handleCallback()
    {
        $handles = get_class_methods($this);
        $isListenerPort = false;
        foreach ($handles as $value) {
            if ('on' == substr($value, 0, 2)) {
                if ($isListenerPort) {
                    if (in_array($value, ['onConnect', 'onClose', 'onReceive', 'onPacket'])) {
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
     * 引导启动
     * @param swoole_server|swoole_server_port $swoole
     * @return $this
     */
    protected function bootstrap($swoole = null)
    {
        $this->server = null === $swoole ? $this->initServer() : $swoole;
        $this->doListen();
        $this->server->set($this->config);
        $this->handleCallback();
        return $this;
    }


    public function start()
    {
        try {
            $this->bootstrap();
            $this->server->start();
        } catch (UpaddException $e) {
            throw new UpaddException($e->getMessage());
        }
    }


}