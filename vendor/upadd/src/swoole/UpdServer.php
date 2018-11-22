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
use Log;
use Upadd\Bin\UpaddException;
use Swoole\Server as swoole_server;

abstract class UpdServer extends Server{


   public function configure()
   {
       $config = Config::get('swoole@tcp_param');
       $config['daemonize'] = Config::get('swoole@daemonize');
       return $config;
   }

    /**
     * @param swoole_server $server
     * @param string $data
     * @param array $client_info
     * @return void
     */
    public function onPacket(swoole_server $server, $clinetData, array $clientInfo)
    {
//        p($clientInfo,true);
        $content = $this->doWork($server, $clinetData, $clientInfo);
//        Log::cmd($content);
//        $server->sendto($clientInfo['address'], $clientInfo['port'], $content);
    }

    /**
     * 业务逻辑代码
     * 回调思路实现
     * @param $param
     * @return mixed
     */
    abstract protected function doWork($server, $clinetData, $clientInfo);

}