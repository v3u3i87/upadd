<?php

namespace Upadd\Bin;

use Upadd\Bin\Api\DiInterface;
use Upadd\Bin\UpaddException;

class Di
{

    /**
     * @var array
     */
    public static $service = [];

    public static $configData = [];


    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->service[$name] = $value;
    }


    public static function setConfigData($config)
    {
        static::$configData = $config;
    }


    public static function getConfig()
    {
        return static::$configData;
    }


    /**
     * @param $name
     * @return mixed
     * @throws \Upadd\Bin\UpaddException
     */
    public function __get($name)
    {
        if (isset(static::$service[$name])) {
            return static::$service[$name];
        } else {
            throw new UpaddException("Service '" . $name . "' wasn't found in the dependency injection container");
        }
    }


    /**
     * @param array $import
     * @throws \Upadd\Bin\UpaddException
     */
    public static function import(array $import)
    {
        if (is_array($import)) {
            if (count(static::$service) >= 1) {
                static::$service = array_merge(static::$service, $import);
            } else {
                static::$service = $import;
            }
        } else {
            throw new UpaddException("import not Service '");
        }
    }


    /**
     * @param $name
     * @param $definition
     */
    public static function set($name, $definition)
    {
        static::$service[$name] = $definition;
        return static::$service[$name];
    }


    /**
     * @param $name
     * @return mixed
     * @throws \Upadd\Bin\UpaddException
     */
    public static function get($name)
    {
        if (isset(static::$service[$name])) {
            $instance = static::$service[$name];
        } else {
            throw new UpaddException("Service '" . $name . "' wasn't found in the dependency injection container");
        }

        if (is_object($instance)) {
            if ($instance instanceof DiInterface) {
                $instance->binding($instance);
            }
        } else {
            throw new UpaddException("Service '" . $name . "' not is object");
        }
        return $instance;
    }

    /**
     * @param $name
     * @throws \Upadd\Bin\UpaddException
     */
    public static function del($name)
    {
        if (isset(static::$service[$name])) {
            unset(static::$service[$name]);
        } else {
            throw new UpaddException("Service '" . $name . "' wasn't found in the dependency injection container");
        }
    }

    /**
     * @return array
     */
    public static function all()
    {
        return static::$service;
    }

}