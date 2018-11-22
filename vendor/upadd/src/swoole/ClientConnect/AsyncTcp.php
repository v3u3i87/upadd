<?php

namespace Upadd\Swoole\ClientConnect;

use Upadd\Swoole\Client;
use Upadd\Swoole\Lib\Help;
use Config;
use Swoole\Client as swoole_client;

/**
 * Class AsyncTcp
 * @package Upadd\Swoole\Client
 */
class AsyncTcp extends Client
{

    public function __construct($address = null, $data = null)
    {
        parent::__construct($address, $data);
        $this->client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
        // 绑定回调
        $this->client->on('connect', array($this, 'onConnect'));
        $this->client->on('receive', array($this, 'onReceive'));
        $this->client->on('error', array($this, 'onClose'));
        $this->client->on('close', array($this, 'onError'));
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

    public function connect()
    {
        $fp = $this->client->connect($this->host, $this->port);
        if (!$fp) {
            echo "Error: {$fp->errMsg}[{$fp->errCode}]\n";
            return;
        }
    }

    /**
     * 发送数据启动通道
     * @return bool
     */
    public function onConnect()
    {
        if (empty($this->data)) {
            return false;
        }
        $this->client->send($this->data);
        $this->client->send('\r\n\r\n');
    }


    public function onReceive($cli, $data)
    {
        echo "Get Message From Server: {$data}\n";
    }


    public function onClose($cli)
    {
        echo "Client close connection\n";
    }

    public function onError($cli)
    {

    }


}