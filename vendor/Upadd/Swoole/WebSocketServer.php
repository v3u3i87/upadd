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
use swoole_websocket_server;
use swoole_websocket_frame;
use swoole_http_request;


abstract class WebSocketServer extends Server{


   public function configure()
   {
       $config = Config::get('swoole@webSocketParam');
       $config['daemonize'] = Config::get('swoole@daemonize');
       return $config;
   }

    /**
     * @param swoole_websocket_server $server
     * @param swoole_http_request $request
     * @return mixed
     */
    public function onOpen(swoole_websocket_server $server, swoole_http_request $request)
    {
        return $this->doOpen($server, $request);
    }

    /**
     * @param swoole_websocket_server $server
     * @param swoole_http_request $request
     * @return mixed
     */
    abstract public function doOpen(swoole_websocket_server $server, swoole_http_request $request);

    /**
     * @param swoole_server $server
     * @param swoole_websocket_frame $frame
     * @return mixed
     */
    public function onMessage(swoole_server $server, swoole_websocket_frame $frame)
    {
        return $this->doMessage($server, $frame);
    }

    /**
     * @param swoole_server $server
     * @param swoole_websocket_frame $frame
     * @return mixed
     */
    abstract public function doMessage(swoole_server $server, swoole_websocket_frame $frame);

    /**
     * @return swoole_websocket_server
     */
    public function initServer()
    {
        return new swoole_websocket_server($this->host, $this->port);
    }

}