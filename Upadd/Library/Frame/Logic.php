<?php
/**
 +----------------------------------------------------------------------
 | UPADD [ Can be better to Up add]
 +----------------------------------------------------------------------
 | Copyright (c) 20011-2014 http://upadd.cn All rights reserved.
 +----------------------------------------------------------------------
 | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 +----------------------------------------------------------------------
 | 
 **/
is_upToken();
//逻辑类
class Logic { 
	
	/**
	 * 数据对象
	 * @var unknown
	 */
	public $_model;
	
	public function __construct(){}
	
	/**
	 * 对应调用数据层对象
	 * @param unknown $model
	 * @return void|unknown
	 */
	public function loadModel($model)
	{
		$model = ucfirst($model);
		//ucwords(strtolower($model));
		$fileName = MODEL_PAHT . $model . IS_EXP;
		if(!file_exists($fileName)){
			exit("该模型文件不存在,".$fileName);
		}
		if (!is_file($fileName)) {
			return;
		}
		if (class_exists($model)) {
			$this->_model = new $model();
			return $this->_model ;
		}
	}
	
	/**
	 * 分页
	 * @param unknown $limit
	 */
	public function getLimit($limit){
		return $this->_model->getLimit($limit);
	}
	
	/**
	 * 获取表总行数
	 * @param string $where
	 */
	public function getTotal($table=null,$where=null){
		return $this->_model->_getTotal($table,$where);
	}

	/**
	 * 加密
	 * @param unknown $post
	 * @return string
	 */
	public function jm($post){
		return md5(is_conf('JM').$post);
	}
	
	
	
}