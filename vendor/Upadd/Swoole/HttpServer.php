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

abstract class HttpServer extends Server{


    /**
     * 开启http服务
     * @throws UpaddException
     */
    public function start()
    {
        if(Config::get('swoole@is_http'))
        {
            $this->httpConfig = Config::get('swoole@httpParam');
            $this->httpConfig['daemonize'] = Config::get('swoole@daemonize');
            $this->_obj->set($this->httpConfig);
            $this->_obj->start();
        }else{
            throw new UpaddException('swoole http server There is no open');
        }
    }

//    protected function request($request)
//    {
//        if (isset($request->post)) {
//            $params = $request->post;
//        }
//        $url = trim($request->server["request_uri"]);
//    }


    protected function response($request,$response)
    {
        $url = trim($request->server["request_uri"]);
        $params = [];
        if (isset($request->get))
        {
            $params = $request->get;
        }

        if (isset($request->post))
        {
            if (count($params) >= 1)
            {
                $params = array_merge($params, $request->post);
            }else{
                $params = $request->post;
            }
        }

//        Data::swooleData($params);
        $response->status(200);
        return $response->end('test----');
    }

}