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
//验证类
class Check extends Verify{
	
    //判断验证是否通过，默认通过
    public $_flag = true;
    //错误消息集
    public  $_message = array();
        	
    public function __construct() {}

	
    /**
     * 返回提示信息
     * @return array 
     */
    public function __info(){
    	if(isset($this->_message) && is_array($this->_message)){
    		return $this->_message;
    	}
    }
   
    
    
    
    
    
    
	
	
}
//End Check class