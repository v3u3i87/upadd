<?php

namespace Upadd\Bin;

abstract class Factory{

    public static $instance = array();


    /**
     * 导入项目
     * @param $_work
     */
    public static function Import($_work){
        static::$instance = $_work;
    }


    /**
     * 获取名称
     * @return mixed
     */
    public static function getName()
    {
        if(isset(static::$instance[static::getClassObj()]))
        {
            return static::$instance[static::getClassObj()];
        }
        return static::getClassObj();
    }


    public static function __callStatic($method, $args)
    {

        $action = static::getName();

        switch (count($args))
        {
            case 0:
                return $action->$method();

            case 1:
                return $action->$method($args[0]);

            case 2:
                return $action->$method($args[0], $args[1]);

            case 3:
                return $action->$method($args[0], $args[1], $args[2]);

            case 4:
                return $action->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array(array($action, $method), $args);
        }
    }

}