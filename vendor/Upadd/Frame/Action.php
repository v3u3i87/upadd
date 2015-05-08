<?php

namespace Upadd\Frame;

use Upadd\Bin\View\Templates;
/**
	+----------------------------------------------------------------------
	| UPADD [ Can be better to Up add]
	+----------------------------------------------------------------------
	| Copyright (c) 20011-2015 http://upadd.cn All rights reserved.
	+----------------------------------------------------------------------
	| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
	+----------------------------------------------------------------------
	| Author: Richard.z <v3u3i87@gmail.com>
 **/

// 控制器
class Action {
	
	/**
	 * html
	 * @var unknown
	 */
	public $_html = null;
	
	/**
	 * 获取参数
	 * @var unknown
	 */
	public $_u = array ();

    public $_model = null;

	public function __construct() 
	{
        $this->Run();
	}


    protected function Run(){
        if($this->_html===null){
            $this->_html = Templates\Templates::getHtml();
        }
    }




}
//End Action class