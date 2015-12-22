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

use Upadd\Bin\UpaddException;
use Upadd\Bin\View\Templates;

// 控制器
class Action {


    /**
     * 权限对象
     * @var null
     */
    public $_rbac = null;

    public $_templates;

	public function __construct(){
        $this->_templates = new Templates();
    }

    /**
     * 设置模板变量
     * @param $key
     * @param $val
     * @throws \Upadd\Bin\UpaddException
     */
    public function val($key,$val)
    {
        if($key && $val)
        {
            return $this->_templates->val($key,$val);
        }
        throw new UpaddException('$key或$val没有设置');
    }

    /**
     * 设置模板文件
     * @param $file
     * @throws UpaddException
     */
    public function view($file)
    {
        if($file)
        {
            return $this->_templates->path($file);
        }
        throw new UpaddException('模板文件没有设置');
    }


    /**
     * 设置模板控制器
     * @param $name
     */
    public function setViewAction($name)
    {
        $lode = lode('\\',$name);
        if(isset($lode[2]))
        {
            $name = $lode[2];
            if(substr($name, -6)=='Action')
            {
                $name = substr($name, 0,-6);
            }
            $this->_templates->setPath(strtolower($name));
        }else{
            throw new UpaddException('控制器模板目录设置失败');
        }
    }







}