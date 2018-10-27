<?php

namespace Upadd\Swoole;

use Config;
use Upadd\Bin\Http\Dispenser;
use Upadd\Bin\UpaddException;
use Swoole\Server as swoole_server;
use Swoole\Http\Request as swoole_http_request;
use Swoole\Http\Response as swoole_http_response;
use Swoole\Http\Server as swoole_http_server;


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


    /**
     * @return \swoole_server
     */
    public function initServer()
    {
        $this->dispenser = new Dispenser();
        return new swoole_http_server($this->host, $this->port);
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
        $content = $this->dispenser->swoole($request);
        $response->status($content['code']);
        if (isset($content['header'])) {
            foreach ($content['header'] as $key => $val) {
                $response->header($key, $val);
            }
        }
        return $response->end($content['data']);
    }

    /**
     * 完成
     * @param $fd
     * @param $results
     * @return array
     */
    protected function results($fd, $results)
    {
        return [
            'fd' => $fd,
            'results' => $results
        ];
    }


}