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
     * 开启TCP服务
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



}