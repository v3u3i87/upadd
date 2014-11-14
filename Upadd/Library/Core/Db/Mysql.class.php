<?php
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
class Mysql extends Db{
	
	//用于存放实例化的对象
	static protected $_instance = null;
	
	//公共静态方法获取实例化的对象
	static public function getMysql($link) {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self($link);
		}
		return self::$_instance;
	}
	
	//私有克隆
	private  function __clone() {}
	
	public function __construct($link){
		if(is_array($link)){
			$_link = @mysql_connect($link['db_host'].':'.$link['db_port'],$link['db_user'],$link['db_pass']) or die('数据库账户错误'.$this->log());
			$sql= @mysql_select_db($link['db_name'],$_link) or die('数据库连接错误'.$this->log());
			mysql_query('SET NAMES '.$link['db_charset']) or die('数据库编码设置错误'.$this->log());
		}
	}
		
	/**
	 * 查询
	 */
	 public  function select($sql){
	 	if($sql){
	 		$result = $this->query($sql);
	 		$_result = array();
	 		while (!!$row = mysql_fetch_assoc($result)){
	 			$_result[]=$row;
	 		}
	 		$this->out($result);
	 		return $_result;
	 	}
	 }
	 
	 
	public function find($sql){
		$result = $this->query($sql);
		$data = mysql_fetch_assoc($result);
		$this->out($result);
		return $data;
	}	 
	
	 /**
	  * 获取下条自增ID
	  * @param unknown $sql
	  * @return multitype:multitype:
	  */
	 public function getNextId($sql){
	 	if($sql){
	 		$_result = $this->select($sql);
	 		return $_result[0]['Auto_increment'];
	 	}
	 }
	 
	 /**
	  * 获取表总行数
	  * @param unknown $sql
	  */
	 public function getTotal($sql){
	 	$total = mysql_fetch_row($this->query($sql));
	 	return $total[0];
	 }
	 
	 
	 /**
	  *  获取表字段 并返回索引数组
	  * @name  
	  * @param string $t
	  * @return multitype:
	  */
	 public function getField($sql=null){
 		$_result = $this->select($sql);
 		$field = '';
 		foreach ($_result as $k=>$v){
 				$field .= $v['Field'].',';
 		}
 		$field = substr($field,0,-1);
 		$field = explode(',',$field);
 		return $field;
	 }
	 
	 /**
	  * 返回当前新增ID
	  * @return number
	  */
	 public function getId($sql=null){
	 	return mysql_insert_id();
	 }
	 	 
	 /**
	  * 对外提供提交SQL
	  */
	 public function sql($sql){
	 	return $this->query($sql);
	 }
	 
	 //释放结果集
	 protected function out($result){
	 	if(is_resource($result)){
	 		$result = mysql_free_result($result);
	 		$result = null;
	 	}else{
	 		echo 'out is to on';
	 	}
	 }
	 
	 /**
	 * 提交SQL
	 */
	 protected function query($sql){
	 	DataLog::write($sql,'log.sql');//记录SQL
	 	$result = @mysql_query($sql) or die('当前操作:SQL语句有误!'.$this->log($result));
	 	return $result;
	 }
	
	 //记录SQL错误
	 protected function log($result=''){
	 	$con = 'URL:'.$_SERVER['REQUEST_URI']."\r\r错误:".mysql_error().$result."\r\r错误发生时间:".date("Y-m-d H:i:s")."\r\n";
	 	DataLog::write($con,'SqlError.sql');//记录SQL
	 }
	
	
	
	
	
}
