<?php

namespace Upadd\Frame;

/**
 * +----------------------------------------------------------------------
 * | UPADD [ Can be better to Up add]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2011-2016 http://upadd.cn All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: Richard.z <v3u3i87@gmail.com>
 **/

use Upadd\Frame\Query;
use Upadd\Bin\Tool\Log;
use Upadd\Bin\UpaddException;

class Model
{

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
    public $_query = null;

    /**
     * 数据库信息
     * @var array|string
     */
    private $_dbInfo = [];

    /**
     * 默认库
     * @var string
     */
    protected $use = 'local';


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
     * 派发链接DB对象
     */
    protected function distribution()
    {
        if (conf('database@many') === true) {
            foreach ($this->_dbInfo as $key => $value) {
                if ($this->use === $value['use']) {
                    $this->_dbInfo = $value;
                    continue;
                }
            }
        }
    }

    /**
     * 链接数据库
     */
    protected function connection()
    {
        $this->db_prefix = $this->_dbInfo ['prefix'];
        /**
         * 设置表名
         */
        $this->setTableName($this->_table);
        $this->_db = new \Upadd\Bin\Db\LinkPdoMysql($this->_dbInfo);
        $this->_query = new Query($this->_db, $this->getTableName(), $this->_primaryKey, $this->db_prefix);
    }


    /**
     * 设置表名称
     * @param $table
     */
    public function setTableName($table)
    {
        if ($this->_table !== $this->db_prefix . $table) {
            $this->_table = $this->db_prefix . $table;
        }
    }

    /**
     *  返回所有的库表
     * @return mixed in array
     */
    public function getTableAll()
    {
        return $this->_query->showTables();
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
        if (array_key_exists($key, $this->_query->parameter)) {
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
        return $this->_query->getParameter();
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
