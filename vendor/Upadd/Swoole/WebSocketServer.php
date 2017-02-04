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

abstract class WebSocketServer extends Server{


   public function configure()
   {
       $config = Config::get('swoole@webSocketParam');
       $config['daemonize'] = Config::get('swoole@daemonize');
       return $config;
   }


}