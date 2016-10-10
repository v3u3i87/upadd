<?php

namespace Upadd\Frame;

use Upadd\Frame\Query;
//use Upadd\Bin\Tool\Log;
//use Upadd\Bin\UpaddException;

class Model extends Query
{

    /**
     * 初始化
     * Model constructor.
     * @param null $db
     */
    public function __construct($dbInfo = null)
    {
        if ($dbInfo !== null) {
            $this->_dbInfo = $dbInfo;
        } else {
            $this->_dbInfo = conf('database@db');
            //派发数据库
            $this->distribution();
        }
        $this->connection();
    }

    /**
     * 获取为止参数
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->parameter))
        {
            return $this->parameter[$key];
        } else {
            return null;
        }
    }

    /**
     * 设置未知的参数
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->parameter [$key] = $value;
    }

    /**
     * 获取设置的参数
     * @return mixed
     */
    public function getData()
    {
        return $this->getParameter();
    }

    public function __call($name, $parameters)
    {
        try {
            return call_user_func_array(array($this, $name), $parameters);
        }catch(\Exception $e){
            p($e);
        }
    }

    public static function __callStatic($method, $parameters)
    {
        try {
            /**
             * 实例化自己
             */
            $instance = new static;
            return call_user_func_array([$instance, $method], $parameters);
        }catch(\Exception $e){
            p($e);
        }

    }

}
