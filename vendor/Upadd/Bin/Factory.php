<?php
/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 15/12/8
 * Time: 16:36
 * Name:
 */
namespace Upadd\Bin;

class Factory{

    public static $instance = array();

    public static function setInstance($name=null){
        if(is_array($name)){
            static::$instance = array_merge(static::$instance,$name);
        }else{
            static::$instance[] = $name;
        }
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::$instance;
        p($instance);
        switch (count($args))
        {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array(array($instance, $method), $args);
        }
    }

}