<?php

namespace Upadd\Frame;

/**
 * +----------------------------------------------------------------------
 * | UPADD [ Can be better to Up add]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2011-2017 https://github.com/v3u3i87/upadd All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: Richard.z <v3u3i87@gmail.com>
 **/

use Upadd\Bin\Db\Pretreatment;

class Model
{

    /**
     *
     * @var null
     */
    private $db = null;

    protected $use = 'local';

    /**
     * 表名
     *
     * @var unknown
     */
    protected $_table = null;

    /**
     * 主键或关键字
     * @var null
     */
    protected $_primaryKey = null;
    /**
     * 是否自动创建时间戳
     * @var bool
     */
    protected $_automaticityTime = false;

    /**
     * 初始化
     * Model constructor.
     * @param null $db
     */
    public function __construct($dbInfo = null)
    {
        $this->connection($dbInfo);
    }

    /**
     * 链接数据库
     * @param $dbInfo
     */
    protected function connection($dbInfo)
    {
        if (!$this->db) {
            $this->db = new Pretreatment($dbInfo);
        }
        //链接数据库对象
        $this->db->use = $this->use;
        //表名
        $this->db->_table = $this->_table;
        //主键
        $this->db->_primaryKey = $this->_primaryKey;
        //自动创建时间戳
        $this->db->_automaticityTime = $this->_automaticityTime;
        //实例化
        $this->db->instance();
    }


    /**
     *  返回所有的库表
     * @return mixed in array
     */
    public function getAllTable()
    {
        return $this->db->getAllTable();
    }


    /**
     * 对外提供DB方法
     * @return PDO
     */
    public function db()
    {
        return $this->db;
    }

    /**
     * 获取为止参数
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        if (array_key_exists($key, $this->db->parameter)) {
            return $this->db->parameter[$key];
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
        $this->db->parameter [$key] = $value;
    }

    /**
     * 获取设置的参数
     * @return mixed
     */
    public function getData()
    {
        return $this->db->parameter;
    }


    /**
     *
     * @param $name
     * @param $parameters
     * @return mixed
     */
    public function __call($name, $parameters)
    {
        return call_user_func_array(array($this->db, $name), $parameters);
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array(array((new static())->db, $method), $parameters);
    }


}
