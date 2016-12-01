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

    /**
     * 模板对象
     * @var
     */
    protected $_templates;

    /**
     * 响应返回对象
     * @var string
     */
    protected $_responseType = 'json';

    /**
     * 实例化
     */
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
    protected function val($key=null,$val=null)
    {
        return $this->_templates->val($key,$val);
    }

    /**
     * 返回模板对象
     * @return mixed
     */
    protected function getTemplates()
    {
        return $this->_templates;
    }

    /**
     * 设置模板文件
     * @param $file
     * @throws UpaddException
     */
    protected function view($file)
    {
        if($file)
        {
            return $this->_templates->bound($file);
        }
        throw new UpaddException('模板文件没有设置');
    }


    /**
     * 设置响应类型
     * @param $type
     */
    protected function setResponseType($type)
    {
        $this->_responseType = $type;
    }

    /**
     * 返回响应类型
     * @return string
     */
    public function getResponseType()
    {
        return $this->_responseType;
    }









}