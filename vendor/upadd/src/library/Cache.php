<?php
namespace Upadd\Bin;

use Upadd\Bin\Config\Configuration;
use Upadd\Bin\Cache\getMemcache;
use Upadd\Bin\Cache\getRedis;

class Cache
{


    public function __call($name, $parameters)
    {
        return call_user_func_array($name, $parameters);
    }


    public static function __callStatic($method, $parameters)
    {

        if ($method == 'redis') {
            if (Configuration::get('sys@is_use_redis')) {
                return (new getRedis());
            }
        }

        if ($method == 'memcache') {
            if (Configuration::get('sys@is_use_memcache')) {
                return (new getMemcache());
            }
        }
    }


}