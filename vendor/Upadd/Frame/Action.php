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
class Action
{

    public $_templates;


    public function init()
    {
        $this->_templates = new Templates();
    }

    /**
     * 设置模板变量
     * @param $key
     * @param $val
     * @throws \Upadd\Bin\UpaddException
     */
    public function val($key=null,$val=null)
    {
        return $this->_templates->val($key,$val);
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
            return $this->_templates->bound($file);
        }
        throw new UpaddException('模板文件没有设置');
    }







}