<?php

namespace Upadd\Frame;

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
use Upadd\Bin\View\Templates;


// 控制器
class Action {


    /**
     * 模板对象
     * @var null|Templates
     */
    public $_view = null;

    /**
     * 权限对象
     * @var null
     */
    public $_rbac = null;

	public function __construct(){
        if($this->_view === null){
            $this->_view = new Templates();
        }
    }

    /**
     * 设置模板控制器
     * @param $name
     */
    public function setViewAction($name){
        $lode = lode('\\',$name);
        if(isset($lode[2])){
            $name = $lode[2];
        }
        $this->_view->setPath(strtolower ($name));
    }





}
//End Action class