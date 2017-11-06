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

use Upadd\Bin\UpaddException;
use swoole_server;

abstract class UpdServer extends Server{


   public function configure()
   {
       $config = Config::get('swoole@webSocketParam');
       $config['daemonize'] = Config::get('swoole@daemonize');
       return $config;
   }

    /**
     * @param swoole_server $server
     * @param string $data
     * @param array $client_info
     * @return void
     */
    public function onPacket(swoole_server $server, $data, array $client_info)
    {
        $content = $this->doPacket($server, $data, $client_info);

        $server->sendto($client_info['address'], $client_info['port'], $content);
    }

    /**
     * @param swoole_server $server
     * @param $data
     * @param $client_info
     * @return mixed
     */
    abstract public function doPacket(swoole_server $server, $data, $client_info);

    /**
     * 业务逻辑代码
     * 回调思路实现
     * @param $param
     * @return mixed
     */
    abstract protected function doWork($param = [], $client = []);

}