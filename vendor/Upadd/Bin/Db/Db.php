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
abstract class Db {
	
	/**
	 * 查询
	 */
	abstract protected function select($sql);
	
	/**
	 * 获取下条自增ID
	 *
	 * @param unknown $sql        	
	 * @return multitype:multitype:
	 */
	abstract protected function getNextId($sql);
	
	/**
	 * 获取表总行数
	 *
	 * @param unknown $sql        	
	 */
	abstract protected function getTotal($sql);
	
	/**
	 * 获取表字段 并返回索引数组
	 *
	 * @name
	 *
	 * @param string $t        	
	 * @return multitype:
	 */
	abstract protected function getField($sql = null);
	
	/**
	 * 返回当前新增ID
	 *
	 * @return number
	 */
	abstract protected function getId($sql = null);
	
	/**
	 * 对外提供SQL语句查询
	 *
	 * @param unknown $sql        	
	 */
	abstract protected function sql($sql);
	
	/**
	 * 释放结果集
	 *
	 * @param unknown $sql        	
	 */
	abstract protected function out($sql);
	
	/**
	 * 单条查询
	 *
	 * @param unknown $sql        	
	 */
	abstract protected function find($sql);
	
	/**
	 * 提交SQL
	 */
	abstract protected function query($sql);
	
	/**
	 * 记录SQL
	 *
	 * @param unknown $sql        	
	 */
	abstract protected function log($sql);
}