<?php 
/******************************************************************************
 * Upcms-v-0.0.1 是由Upadd.cn开发和维护的一套采用MVC结构的upadd框架开发的php程序!
*-------------------------------------------------------------------------------
* 版权所有: CopyRight By upadd.cn
* 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
*-------------------------------------------------------------------------------
* $Author:Richard.Z
* $Blog:http://www.zmq.cc
* $Dtime:2014.02.08
*******************************************************************************/
defined('UPADD_HOST') or exit();
class Moban{ 
	
	public $vars =  array();

	public $_path;
	
	static private $_instance = null;
	
	//公共静态方法获取实例化的对象
	static public function getMoban() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function __construct(){ 
		//$this->vars = array(); 
	}

	public function getpath($path=''){
		if(isset($path)){
			$this->_path = APP_PAHT.'html/'.$path.'/';
		}
	}
	
	public function getVal($name,$value){
		$this->vars[$name] = $value; 
	} 
	
	public function getFile($file){
		extract($this->vars); 
		ob_start();
		//赋值模板文件
		$_PathFileHtml = $this->_path.$file;
		//引入模板文件  
		include ($_PathFileHtml);
		$contents = ob_get_contents(); 
		ob_end_clean(); 
		echo $contents; 
	} 
	
}//edn
