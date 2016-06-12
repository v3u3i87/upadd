<?php
namespace Upadd\Bin\Db;

use Upadd\Bin\Log;

/**
 +----------------------------------------------------------------------
 | UPADD [ Can be better to Up add]
 +----------------------------------------------------------------------
 | Copyright (c) 20011-2014 http://upadd.cn All rights reserved.
 +----------------------------------------------------------------------
 | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 +----------------------------------------------------------------------
 | Author: Richard.z <v3u3i87@gmail.com>
 **/
class Mysql implements Db {
	
	/**
	 * 对象
	 *
	 * @var unknown
	 */
	protected $_linkID = null;

    /**
     * 当前SQL
     * @var null
     */
    public $_sql = null;

	public function __construct($link) {
		if ($this->_linkID === null) {
			if (is_array ( $link )) {
				$this->_linkID = @mysql_connect ( $link ['host'] . ':' . $link ['port'], $link ['user'], $link ['pass'] ) or die ( '数据库账户错误' . $this->log () );
				$this->_linkID = @mysql_select_db ( $link ['name'], $this->_linkID ) or die ( '数据库连接错误' . $this->log () );
				$this->query ( 'SET NAMES ' . $link ['charset'] );
			}
		}
	}


	/**
	 * 查询
	 */
	public function select($sql) {
		if ($sql) {
			$result = $this->query ( $sql );
			$_result = array ();
			while ( ! ! $row = mysql_fetch_assoc ( $result ) ) {
				$_result [] = $row;
			}
			$this->out ( $result );
			return $_result;
		}
	}

	public function find($sql) {
		$result = $this->query ( $sql );
		$data = mysql_fetch_assoc ( $result );
		$this->out ( $result );
		return $data;
	}
	
	/**
	 * 获取下条自增ID
	 *
	 * @param unknown $sql        	
	 * @return multitype:multitype:
	 */
	public function getNextId($sql)
    {
		if ($sql) {
			$_result = $this->select ( $sql );
			return $_result [0] ['Auto_increment'];
		}
	}
	
	/**
	 * 获取表总行数
	 *
	 * @param unknown $sql        	
	 */
	public function getTotal($sql) {
		$total = mysql_fetch_row ( $this->query ( $sql ) );
		return $total [0];
	}
	
	/**
	 * 获取表字段 并返回索引数组
	 *
	 * @name
	 *
	 * @param string $t        	
	 * @return multitype:
	 */
	public function getField($sql = null) {
		$_result = $this->select ( $sql );
		$field = '';
		foreach ( $_result as $k => $v ) {
			$field .= $v ['Field'] . ',';
		}
		$field = substr ( $field, 0, - 1 );
		$field = explode ( ',', $field );
		return $field;
	}
	
	/**
	 * 返回当前新增ID
	 *
	 * @return number
	 */
	public function getId($sql = null) {
		return mysql_insert_id ();
	}
	
	/**
	 * 对外提供提交SQL
	 */
	public function sql($sql) {
		return $this->query ( $sql );
	}
	
	// 释放结果集
    public function out($result) {
		if (is_resource ( $result )) {
			$result = mysql_free_result ( $result );
			$result = null;
		} else {
			echo 'out is to on';
		}
	}
	
	/**
	 * 提交SQL
	 */
	public function query($sql) {
        Log::write ( $sql, 'log.sql' ); // 记录SQL
        $this->_sql = $sql;
		$result = @mysql_query ( $sql ) or die ( '当前操作:SQL语句有误!' . $this->log ( $result ) );
		return $result;
	}
	
	// 记录SQL错误
    public function log($result = '') {
		$con = 'URL:' . $_SERVER ['REQUEST_URI'] . "\r\r错误:" . mysql_error () . $result . "\r\r错误发生时间:" . date ( "Y-m-d H:i:s" ) . "\r\n";
        Log::write ( $con, 'SqlError.sql' ); // 记录SQL
	}


    /**
     * 开启事务
     * @return mixed
     */
    public function begin(){

    }

    /**
     * 提交事务并结束
     * @return mixed
     */
    public function commit(){

    }

    /**
     * 回顾事务
     * @return mixed
     */
    public function rollback(){

    }

    /**
     * 返回一条SQL语句s
     * @param $type as exit or
     * @return mixed
     */
    public function printSql($type=1){
        $type ? p($this->_sql) : p($this->_sql,1);
    }

}
