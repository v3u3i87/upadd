<?php

namespace Upadd\Swoole\ClientConnect;

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 2018/2/6
 * Time: 下午2:59
 * Name:
 */
use Upadd\Swoole\Client;
use Config;

/**
 * Class SyncTcp
 * @package Upadd\Swoole\Client
 */
class SyncTcp extends Client
{


    public function __construct($address = null, $data = null)
    {
        parent::__construct($address, $data);
        $this->client = new \swoole_client(SWOOLE_SOCK_TCP);
        // 创建一个开启SSL加密的TCP客户端
//        $client = new swoole_client(SWOOLE_TCP|SWOOLE_SSL);
        // 创建一个可以在FPM中使用的长连接客户端
//        $client = new swoole_client(SWOOLE_TCP|SWOOLE_KEEP);
    }

    /**
     * 设置私有
     */
    private function setConfig()
    {
        $config = Config::get('swoole@tcpClinet');
        if ($this->is_ssl) {
            $config = array_merge($config, $this->sslPath);
        }
        return $config;
    }

    /**
     * @param array $config
     */
    public function onSet($config = [])
    {
        if ($config) {
            $this->client->set($config);
        } else {
            $this->client->set($this->setConfig());
        }
    }

    /**
     * @return bool
     */
    public function connect()
    {
        $fp = $this->client->connect($this->host, $this->port, -1);
        if (!$fp) {
            echo "Error: {$fp->errMsg}[{$fp->errCode}]\n";
            return false;
        }
        return true;
    }


    /**
     * @param null $data
     */
    public function send($data = null)
    {
        if (empty($data)) {
            $this->client->send($this->data);
        } else {
            $this->client->send($data);
        }

    }


    /**
     * @return mixed
     */
    public function getResponse()
    {
        $response = $this->client->recv();
        if ($this->is_monitor) {
            print_r($response);
        }
        $this->client->close();
        return $response;
    }

}