<?php
defined('UPADD_HOST') or exit();
class Session{
	
	static public function is_info($data=''){}
	
	/**
	 * Gm参数 //setcookie(name, value, time()+3600);
	 * @param string $k
	 * @param string $v
	 */
	static public function is_gm($k='',$v=''){
		$MD5gm = md5('gm');
		if(isset($_SESSION[$MD5gm][$k])){
			return $_SESSION[$MD5gm][$k];
		}elseif(isset($k) && isset($v)){
			$_SESSION[$MD5gm][$k] = $v;
		}
	}
	
	
	
	static public function is_del(){
		$MD5gm = md5('gm');
		if(isset($_SESSION[$MD5gm])){
			unset ($_SESSION[$MD5gm]);
			return true;
		}
	}
	
	/**
	 * 判断下是否登录
	 * @param string $k
	 * @return boolean
	 */
	static public function is_user($k=''){
		if(isset($k)){
			$MD5gm = md5('gm');
			if(isset($_SESSION[$MD5gm][$k])){
				return true;
			}else{
				header('location:back.php?u=gm&p=login');
				exit();
			}
		}
	}
	
	/**
	 * member参数 //setcookie(name, value, time()+3600);
	 * @param string $k
	 * @param string $v
	 */
	static public function is_add_member($k='',$v=''){
		$MD5gm = md5('member');
		if(isset($_SESSION[$MD5gm][$k])){
			return $_SESSION[$MD5gm][$k];
		}elseif(isset($k) && isset($v)){
			$_SESSION[$MD5gm][$k] = $v;
		}
	}
	
	
	
	/**
	 * 判断下是否登录
	 * @param string $k
	 * @return boolean
	 */
	static public function is_member($k=''){
		if(isset($k)){
			$MD5gm = md5('member');
			if(isset($_SESSION[$MD5gm][$k])){
				return true;
			}else{
				exit('非法访问');
			}
		}
	}
	
	
	static public function is_del_member(){
		$MD5gm = md5('member');
		if(isset($_SESSION[$MD5gm])){
			unset ($_SESSION[$MD5gm]);
			return true;
		}
	}
		
}
