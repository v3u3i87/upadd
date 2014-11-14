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
is_upToken();
class Model{
	
	/**
	 * 表名
	 * @var unknown
	 */
	private $_table=null;
	
	/**
	 * 链接数据库对象
	 * @var unknown
	 */
	private $_db;
	
	/**
	 * WHERE关键字
	 * @var unknown
	 */
	protected $_where = ' WHERE ';
	
	/**
	 * AND关键字
	 * @var unknown
	 */
	protected $_AND = ' AND ';
	
	/**
	 * 分页数
	 * @var unknown
	 */
	protected  $_limit='';
	
	/**
	 * 表前
	 * @var unknown
	 */
	protected $db_prefix;
	
	public function __construct($db=null){
		if($db===null){
			$linkDB = is_conf('default_db');
			$DBinfo = is_conf('Mysql');
			$this->_db =	 $linkDB::getMysql($DBinfo);
		}else{
			$DBinfo = is_conf($db);
			$this->_db =	 $db::get.$db($DBinfo);
		}
		$this->db_prefix = $DBinfo['db_prefix'];
	}
	
	public function setTable($table){
		if($this->_table !== $this->db_prefix.$table){
			$this->_table = $this->db_prefix.$table;
		}
	}
	
	/**
	 * 查询一条
	 * @param string $_field as id ,name,san
	 * @param string $where as id=1 || id=1 AND type=1
	 * @param string $limit as limti 0,1
	 */
	public function _find($_field,$where=null,$limit="LIMIT 1"){
		//拼接WHERE
		$_where= $this->_where;
		//字符串方式
		if (is_string( $where )) $_where .= $where;
	
		//数组的方式
		if (Verify::isArr($where)){
			foreach ($where as $k=>$v) $_where .=$k."='{$v}'".$this->_AND;
			$_where = substr($_where,0,-4);
		}
		$sql = "SELECT {$_field} FROM {$this->_table} {$_where} {$limit}";
		return $this->_db->find($sql);
	}
	
	/**
	 * 查询
	 * @param string $_field
	 * @param string $where
	 */
	public function _show($_field =null,$where=null,$sort=null,$limit=null){
		if(Verify::IsNullString($_field)){
			$Field = ' * ';
		}elseif(Verify::isArr($_field)){
			$Field = lode(',', $_field);
		}elseif(is_string($_field)){
			$Field = $_field;
		}
		
		//拼接WHERE 
		$where != null ? $_where=$this->_where : null;	
		
		//数组的方式
		if (Verify::isArr($where)){
				foreach ($where as $k=>$v){
					$_where .=$k."='{$v}'".$this->_AND;
				}
				$_where = substr($_where,0,-4);
		}
		//字符串方式
		if (is_string($where)){
			$_where .= $where;
		}

		$sql = "SELECT {$Field} FROM {$this->_table} {$_where} {$sort} {$limit}";
		return $this->_db->select($sql);
	}
	
	/**
	 * 新增
	 * @param array $_data
	 */
	public function _add($_data){
		$field = array();
		$value = array();
		foreach ($_data as $k=>$v){$field[] = $k;$value[] = $v;}
		$field = implode(',', $field); $value = implode("','", $value);
		$_sql = "INSERT INTO {$this->_table} ($field) VALUES ('$value')";
		return $this->_db->sql($_sql);
	}
	

	/**
	 * 修改
	 * @param unknown $_data
	 * @param unknown $where
	 */
	public function _edit($_data,$where){
		if(is_array($_data)){
			$_editdata='';
			foreach ($_data as $k=>$v){
				$_editdata .= " $k='$v',";
			}
			$_editdata = substr($_editdata,0,-1);
		}
		$_where = $this->_where;
		//字符串
		if(is_string($where)) $_where .=$where;
		$_sql = "UPDATE {$this->_table} SET {$_editdata}  {$_where} ";
		return $this->_db->sql($_sql);
	}
	
	
	/**
	 * 删除信息
	 * @param string $where
	 */
	public function _del($where=null){
		if($where && is_string($where)){
			$_where = $this->_where.$where;
		}
		$_sql = "DELETE FROM {$this->_table} {$_where} ";
		return $this->_db->sql($_sql);
	}
	
	
	/**
	 * 根据时间和日期组成SQL查询语句
	 * @param $data as type in time or date
	 * @param $data as key field find by date is  or time
	 * @param $data as time find by data
	 * @param $data as start in end find by data
	 */
	public function findData($data){
		if(is_array($data) && !empty($data['key'])){
			if($data['type']=='time' && isset($data['start']) && $data['end']){
				$start = strtotime($data['start']);
				$end = strtotime($data['end']);
			}elseif($data['type']=='data' && isset($data['date'])){
				$start = strtotime($data['date']);
				$end = $start+24*3600;
			}
			$and = " {$data['key']} >= {$start} AND {$data['key']} < {$end} ";
			return $and;
		}
	}
	
	/**
	 * 返回当前新增ID
	 */
	public function _getId(){
		return $this->_db->getId();
	}
	
	
	/**
	 * 获取表总行数
	 * @param string $where
	 */
	public function _getTotal($table=null,$where=null){
		if(empty($table)) 
		{ 
			$table = $this->_table;
		}
		else
		{
			$table = $this->db_prefix.$table;
		}
		if(!empty($where)) $where = $this->_where.' '.$where;
		$sql = " SELECT COUNT(*) FROM {$table} {$where}";
		return $this->_db->getTotal($sql);
	}
	
	/**
	 * 获取表字段
	 */
	public function _getField(){
		$sql= "SHOW COLUMNS FROM {$this->_table}";
		return $this->_db->getField($sql);
	}
		
	/**
	 * 获取下条自增ID
	 */
	public function _getNextId(){
		$_sql = "SHOW TABLE STATUS LIKE '{$this->_table}'";
		return $this->_db->getNextId($_sql);
	}
		
	
	/**
	 * 验证数据
	 */
	public function is_info($key,$val){
		return $this->_find('*', " {$key}='{$val}' ");
	}
	
	
	/**
	 * 使用SQL语句查询
	 * @param unknown $sql
	 */
	public function _query($sql){
		return $this->_db->select($sql);
	}
	
	//获取LIMIT分页
	public function getLimit($_Limit){
		$this->_limit = $_Limit;
		return;
	}
	
	/**
	 * 排序
	 * @param unknown $sort
	 * @return string
	 */
	public function sort($sort,$by=1){
		if($by){
			$where = "ORDER BY {$sort} DESC ";
		}else{
			$where = "ORDER BY {$sort} ASC ";
		}
		return $where;
	}
	
	public function Relation($obj,$_field,$_table,$_where,$limit=null){
		$object = $field = $table='';
		$where = $this->_where;
		foreach ($_field as $k=>$v){
				//拼凑查询字段
				foreach ($v as $name) $field.=$obj[$k].'.'.$name.',';
				$table.=$this->db_prefix.$_table[$k].' as '.$obj[$k].',';
			
		}
		 $field =  substr($field,0,-1);
		 $table =  substr($table,0,-1);
		 $where.=$_where;
		 $sql ="SELECT {$field} FROM {$table} {$where} {$limit}";
		return $this->_db->select($sql);
	}

			
}//End Model class
