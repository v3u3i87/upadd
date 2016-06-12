<?php
namespace Upadd\Bin\Db;

/**
 +----------------------------------------------------------------------
 | UPADD [ Can be better to Up add]
 +----------------------------------------------------------------------
 | Copyright (c) 20011-2014 http://upadd.cn All rights reserved.
 +----------------------------------------------------------------------
 | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 +----------------------------------------------------------------------
  | Author: Richard.z <v3u3i87@gmail.com>
 */
interface Db {

    /**
     * 查询
     * @param $sql
     * @return mixed
     */
	 public function select();
	
	/**
	 * 获取下条自增ID
	 *
	 * @param unknown $sql        	
	 * @return multitype:multitype:
	 */
    public function getNextId();
	
	/**
	 * 获取表总行数
	 *
	 * @param unknown $sql        	
	 */
    public function getTotal();
	
	/**
	 * 获取表字段 并返回索引数组
	 *
	 * @name
	 *
	 * @param string $t        	
	 * @return multitype:
	 */
    public function getField();
	
	/**
	 * 返回当前新增ID
	 *
	 * @return number
	 */
	public function getId();
	
	/**
	 * 对外提供SQL语句查询
	 *
	 * @param unknown $sql        	
	 */
	public function sql($sql=null);


	/**
	 * 单条查询
	 *
	 * @param unknown $sql        	
	 */
    public function find();
	
	/**
	 * 提交SQL
	 */
	public function query();
	
	/**
	 * 记录SQL
	 * @param unknown $sql        	
	 */
	public function log();

    /**
     * 开启事务
     * @return mixed
     */
    public function begin();

    /**
     * 提交事务并结束
     * @return mixed
     */
    public function commit();

    /**
     * 回顾事务
     * @return mixed
     */
    public function rollback();

    /**
     * 返回一条SQL语句
     * @param $status as exit or
     * @return mixed
     */
    public function printSql($status);




}