<?php

namespace Upadd\Frame;
/**
+----------------------------------------------------------------------
| UPADD [ Can be better to Up add]
+----------------------------------------------------------------------
| Copyright (c) 2011-2016 http://upadd.cn All rights reserved.
+----------------------------------------------------------------------
| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
+----------------------------------------------------------------------
| Author: Richard.z <v3u3i87@gmail.com>
 **/

use Upadd\Frame\Query;
use Upadd\Bin\Tool\Log;
use Upadd\Bin\UpaddException;

abstract class Model {

    /**
     * 表名
     *
     * @var unknown
     */
    protected $_table = null;

    /**
     * 表前缀
     * @var null
     */
    protected $db_prefix = null;

    /**
     * 主键或关键字
     * @var null
     */
    protected $_primaryKey = null;

    /**
     * 数据库对象
     *
     * @var unknown
     */
    private $_db;

    /**
     * 查询SQL对象
     * @var null
     */
    protected $_query = null;


    /**
     * 初始化
     * Model constructor.
     * @param null $db
     */
    public function __construct($db = null)
    {
        $DBinfo = conf('database@db');

        $this->db_prefix = $DBinfo ['prefix'];

        /**
         * 设置表名
         */
        $this->setTableName($this->_table);

        /**
         * 选数据库类
         */
        $this->DbType($DBinfo['type'],$DBinfo);
    }


    /**
     * 数据库类型
     * @param null $type
     * @param $DBinfo
     */
    private function DbType($type=null,$DBinfo)
    {
        if($type =='mysql')
        {
            $this->_db = new \Upadd\Bin\Db\Mysql($DBinfo);
        }elseif($type=='pdo_mysql'){
            $this->_db = new \Upadd\Bin\Db\LinkPdoMysql($DBinfo);
        }else{
            throw new UpaddException('数据库连接类型没有选择');
        }
        $this->_query = new Query($this->_db,$this->getTableName(),$this->_primaryKey,$this->db_prefix);
    }


    /**
     * 设置表名称
     * @param $table
     */
    public function setTableName($table)
    {
        if ($this->_table !== $this->db_prefix . $table)
        {
            $this->_table = $this->db_prefix . $table;
        }
    }

    /**
     * 获取表名
     * @return unknown
     */
    public function getTableName()
    {
        return $this->_table;
    }

    /**
     * 获取为止参数
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        if (array_key_exists ( $key, $this->_query->parameter ))
        {
            return $this->_query->parameter[$key];
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
        $this->_query->parameter [$key] = $value;
    }

    /**
     * 获取设置的参数
     * @return mixed
     */
    public function getData()
    {
        return $this->_query->getData();
    }


    public function __call($name, $parameters)
    {
        return call_user_func_array(array($this->_query, $name), $parameters);
    }



    public static function __callStatic($method, $parameters)
    {

        /**
         * 实例化自己
         */
        $instance = new static;

        return call_user_func_array(array($instance->_query, $method), $parameters);
    }




}
