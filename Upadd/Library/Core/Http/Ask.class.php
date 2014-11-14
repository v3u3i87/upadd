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
//http请求类
class Ask{
	//用于存放实例化的对象
	static private $_upadd = null;

	//公共静态方法获取实例化的对象
	static public function getAsk() {
		if (!(self::$_upadd instanceof self)) {
			self::$_upadd = new self();
		}
		return self::$_upadd;
	}
	
	//私有克隆
	private function __clone() {}
	
	//私有构造
	private function __construct() {
		Change::setGetPost();
	}
	
	//获取新增和修改的字段
	public function Filter(Array $_fields) {
		$_Data = array();
		if (Verify::IsArr($_POST) && !Verify::isNullArray($_POST)) {
		 	foreach ($_POST as $_key=>$_value) {
				if (Verify::InArr($_key, $_fields)) {
					$_Data[$_key] = $_value;
				}
			}
		}
		return $_Data;
 	}
 	
 	/**
 	 * 获取表单提交的字段
 	 * @param string $_field
 	 * @return Ambigous <boolean, multitype:unknown >
 	 */
 	public static function getField($_field=''){
 		if(is_array($_GET) || is_array($_POST) || is_array($_field)){
 			if(Verify::IsArr($_GET) && !Verify::isNullArray($_GET)){
 				$data =  self::__TypeGetPost($_GET);
 			}
 			if (Verify::IsArr($_POST) && !Verify::isNullArray($_POST)){
 				$data =  self::__TypeGetPost($_POST);
 			}
 			if (Verify::IsArr($_field) && !Verify::isNullArray($_field)){
 				$data =  self::__TypeGetPost($_field);
 			}
 			return $data;
 		}
 	}
 	
 	/**
 	 * 处理过滤
 	 * @param string $_fields
 	 * @return multitype:unknown |boolean
 	 */
 	protected function __TypeGetPost($_fields=''){
 		$_Data = array();
 		if (Verify::IsArr($_fields) && !Verify::isNullArray($_fields)) {
 			foreach ($_fields as $_key=>$_value) {
 				$_Data[$_key] = $_value;
 			}
 			return $_Data;
 		}else{
 			return false;
 		}
 	}
 	
 	//获取参数处理 Parameter
 	public function setParameter(Array $_param) {
 		$_GetParam = array();
 		foreach ($_param as $_key=>$_value) {
 			if ($_key == 'in') $_value = str_replace(',', "','", $_value);
 			$_GetParam[] = $_value;
 		}
 		return $_GetParam;
 	}
}
