<?php
namespace Upadd\Bin;

use Upadd\Bin\Api\DiInterface;
use Upadd\Bin\UpaddException;

class Di
{

    /**
     * @var array
     */
    public $service = [];

    /**
     * @var array
     */
    public static $shared = [];


    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->service[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Upadd\Bin\UpaddException
     */
    public function __get($name)
    {
        if (isset($this->service[$name])) {
            return $this->service[$name];
        } else {
            throw new UpaddException("Service '" . $name . "' wasn't found in the dependency injection container");
        }
    }


    /**
     * @param array $import
     * @throws \Upadd\Bin\UpaddException
     */
    public function import(array $import)
    {
        if (is_array($import)) {
            if (count($this->service) >= 1) {
                $this->service = array_merge($this->service, $import);
            } else {
                $this->service = $import;
            }
        } else {
            throw new UpaddException("import not Service '");
        }
    }


    /**
     * @param $name
     * @param $definition
     */
    public function set($name, $definition)
    {
        $this->service[$name] = $definition;
    }


    /**
     * @param $name
     * @return mixed
     * @throws \Upadd\Bin\UpaddException
     */
    public function get($name)
    {
        if (isset($this->service[$name])) {
            $instance = $this->service[$name];
        } else {
            throw new UpaddException("Service '" . $name . "' wasn't found in the dependency injection container");
        }

        if (is_object($instance)) {
            if ($instance instanceof DiInterface) {
                $instance->binding($this);
            }
        }else {
            throw new UpaddException("Service '" . $name . "' not is object");
        }
        return $instance;
    }

    /**
     * @param $name
     * @throws \Upadd\Bin\UpaddException
     */
    public function del($name)
    {
        if (isset($this->service[$name])) {
            unset($this->service[$name]);
        } else {
            throw new UpaddException("Service '" . $name . "' wasn't found in the dependency injection container");
        }
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->service;
    }

}