<?php
/**
+----------------------------------------------------------------------
| UPADD [ Can be better to Up add]
+----------------------------------------------------------------------
| Copyright (c) 2011-2015 http://upadd.cn All rights reserved.
+----------------------------------------------------------------------
| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
+----------------------------------------------------------------------
| Author: Richard.z <v3u3i87@gmail.com>
 **/
namespace Upadd\Frame;

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
        $this->_view = new Templates();
    }

    /**
     * 设置模板控制器
     * @param $name
     */
    public function setViewAction($name){
        $lode = lode('\\',$name);
        if(isset($lode[2])){
            $name = $lode[2];
            if(substr($name, -6)=='Action'){
                $name = substr($name, 0,-6);
            }
        }
        $this->_view->setPath(strtolower($name));
    }




}
//End Action class