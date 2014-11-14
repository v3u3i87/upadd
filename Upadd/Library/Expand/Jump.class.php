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
//转跳类
defined('UPADD_HOST') or exit();
class Jump {
	//Is used to store the instantiated object
	static private $_upadd;
	
	//创建模板变量
	public $_html;
	
	static  public function GetJump(){
		if (!(self::$_upadd instanceof self)) {
			self::$_upadd = new self();
		}
		return self::$_upadd;
	}
	// Object cloning
	private function __clone(){}
	
	//Private constructor
	public function	__construct(&$html=''){
		if(isset($html)){
			$this->_html = $html;
		}
		$this->_html->getpath('jump');
	}
	
	/**
	 * 
	 * @param unknown $_url
	 * @param string $_info
	 */
	public function ByGmSucc($_url='', $_info = ''){
		$this->_html->getVal('url',$_url);
		$this->_html->getVal('info',$_info);
		$this->_html->getFile('test.html');
	}
	
	/**
	 * 
	 * @param unknown $_info
	 */
	public function ByGmError($_info){}
	
	/**
	 * 
	 * @param unknown $_url
	 * @param string $_info
	 */
	public function BySucc($_url, $_info =''){
		$this->_html->getVal('url',$_url);
		$this->_html->getVal('info',$_info);
		$this->_html->getFile('succ.html');
	}
	
	/**
	 * 
	 * @param unknown $_info
	 */
	public function ByError($_info){
		$this->_html->getVal('url',Tool::GetPrevPage());
		$this->_html->getVal('info',$_info);
		$this->_html->getFile('error.html');
	}
	
	public static function is_by($bool,$info,$url,$error){
		if(!empty($info) && isset($url)){
			if($bool){
				Tool::GetSucc($info, $url);
			}else{
				Tool::GetError($error);
			}
		}
	}
	
	
}
