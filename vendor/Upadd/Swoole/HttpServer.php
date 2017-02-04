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
use swoole_http_request;
use swoole_http_response;

class HttpServer extends Server
{

    public $dispenser;


    /**
     * 配置文件
     * @return mixed
     */
    public function configure()
    {
        $config = Config::get('swoole@httpParam');
        $config['daemonize'] = Config::get('swoole@daemonize');
        return $config;
    }


    public function getDispenser($dispenser)
    {
        $this->dispenser = $dispenser;
    }

    /**
     * 响应HTTP请求
     * @param \swoole_http_request $request
     * @param \swoole_http_response $response
     */
    public function onRequest(swoole_http_request $request, swoole_http_response $response)
    {
        return $this->response($request, $response);
    }


    /**
     * 请求相应
     * @param $request
     * @param $response
     * @return mixed
     */
    protected function response($request, $response)
    {
        $data = $this->dispenser->swoole($request);
        $response->status(200);
        return $response->end($data);
    }


}