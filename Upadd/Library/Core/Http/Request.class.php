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
/**
 * 请求处理
 */
class Request{
		
	public $_action;
	
	public $_fun;
		
	protected static $in;
	
	public static function getRequest(){
		if (!(self::$in instanceof self)) {
			self::$in = new self();
		}
		return self::$in;
	}
	
	final public function __clone(){}
		
	/**
	 * 执行请求处理
	 */
	private function __construct(){
		self::setRewrite();
		self::Frame();
	}
	
	
	/**
	 * 处理路由请求参数
	 */
	public function setRewrite(){
		if (!empty($_SERVER['PATH_INFO'])) {
			$paths = explode('/', trim($_SERVER['PATH_INFO'],'/'));
			if (!empty($paths) && is_array($paths)) {
				$this->_action = $paths[0];
				$this->_fun = $paths[1];
				$var[$this->_action] = array_shift($paths);
				$var[$this->_fun]  =  array_shift($paths);
				// 解析剩余的URL参数
				preg_replace('@(\w+)\/([^\/]+)@e', '$var[\'\\1\']=strip_tags(\'\\2\');', implode('/',$paths));
				h()->setQuery($var);
			}
		}
	}
	
	/**
	 * 设置默认访问
	 * @return string
	 */
	private  function _u(){
		if($this->_action){
			 return ucfirst($this->_action);
		}elseif(call('u')){
			return ucfirst(call('u'));
		}else{
			return  'Index';
		}
	}
	
	/**
	 * 获取默认方法
	 * @return string
	 */
	private function _p(){
		if($this->_fun){
			return $this->_fun;
		}else{
			return call('p');
		}
	}
	

	/**
	 * 设置HMVC业务机制
	 */
	public function Frame(){		
		if (!Verify::_checkActionName(self::_u())) {return;}
		$u = self::_u();
		$className = $u . 'Action';
		if (class_exists($className)) {
			$action = new $className();
			//设置必带参数
			$action->run(self::_set($u.'Check'),self::_set($u.'Logic'),$u);
			$p = self::_p();
			if(method_exists($action,$p)){
				return $action->$p();
			}else{
				return $action->index();
			}
		}
	}
	
	public function _set($name){
		//$className = self::_u() . $name;
		if($name){
			if(file_exists(CHECK_PAHT.$name.IS_EXP) || file_exists(LOGIC_PAHT.$name.IS_EXP)){
				if (class_exists($name)) return  new $name();
			}
		}
	}
	
	
	
}