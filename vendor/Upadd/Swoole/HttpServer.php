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

class HttpServer extends Server
{

    public $dispenser;

    /**
     * 开启http服务
     * @throws UpaddException
     */
    public function start($dispenser)
    {
        $this->dispenser = $dispenser;

        if (Config::get('swoole@is_http')) {
            $this->httpConfig = Config::get('swoole@httpParam');
            $this->httpConfig['daemonize'] = Config::get('swoole@daemonize');
            $this->_obj->set($this->httpConfig);
            $this->_obj->start();
        } else {
            throw new UpaddException('swoole http server There is no open');
        }
    }

    /**
     * 初始化操作
     * @param $server
     */
    public function initServer($server)
    {

    }

    /**
     * 加载新代码
     * @param $server
     * @param $worker_id
     */
    public function initTask($server, $worker_id)
    {
    }

    /**
     * @param array $param
     * @param array $client
     * @return array
     */
    protected function doWork($param = [], $client = [])
    {
        return $this->toFinish($param['fd'], json($param));
    }


    public function response($request, $response)
    {
        $data = $this->dispenser->swoole($request);
        print_r($data);

        $response->status(200);
//        $src = md5(mt_rand(999, 999) . time());
        return $response->end($data);
    }


}