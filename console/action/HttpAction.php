<?php

namespace console\action;

//use console\swoole\TestHtppServer;
use Config;
use console\swoole\TestServer;
use Upadd\Swoole\HttpServer;
use Upadd\Bin\Client\Http;

class HttpAction extends \Upadd\Frame\Action
{

    public $client = null;


    public function create()
    {
        $http = Config::get('swoole@http');
        HttpServer::create($http['name'],$http['host'])->start();
    }

    public function async()
    {

    }

    public function sync()
    {
    }


}
