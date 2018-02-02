<?php
/**
 * +----------------------------------------------------------------------
 * | UPADD [ Can be better to Up add]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2011-2015 http://upadd.cn All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: Richard.z <v3u3i87@gmail.com>
 **/
namespace Upadd\Frame;

use Upadd\Bin\UpaddException;
use Upadd\Bin\View\Templates;

/**
 * Class Action
 * @package Upadd\Frame
 */
class Action
{

    /**
     * 模板对象
     * @var
     */
    protected $_templates;

    /**
     * 响应类型
     * @var string
     */
    private $_responseType = 'json';

    private $_responseCode = 200;

    private $_responseHeader = [];


    public $setResponseContent;

    protected $getResponseContent;

    /**
     * 实例化
     */
    public function init()
    {
        $this->_templates = new Templates();
    }


    protected function setInit()
    {

    }

    /**
     * 设置模板变量
     * @param $key
     * @param $val
     * @throws \Upadd\Bin\UpaddException
     */
    protected function val($key = null, $val = null)
    {
        return $this->_templates->val($key, $val);
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
        if ($file) {
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

    protected function setResponseCode($code)
    {
        $this->_responseCode = $code;
    }

    /**
     * 设置返回响应头
     * @param $header
     */
    protected function setResponseHeader($header)
    {
        if (count($this->_responseHeader) < 1) {
            $this->_responseHeader = $header;
        } else {
            $this->_responseHeader = array_merge($this->_responseHeader, $header);
        }
    }


    /**
     * @return array
     */
    public function getResponseHeader()
    {
        return $this->_responseHeader;
    }

    /**
     * 返回响应类型
     * @return string
     */
    public function getResponseType()
    {
        return $this->_responseType;
    }

    public function getResponseCode()
    {
        return $this->_responseCode;
    }

    /**
     * 设置响应输出
     */
    public function setResponseContent($content=null)
    {
        if($content){
            $this->getResponseContent = $content;
        }
    }


    /**
     * @param int $code
     * @param $msg
     * @param array $data
     * @return array
     */
    protected function msg($code = 200, $msg = '', $data = [])
    {
        return ['code' => $code, 'msg' => $msg, 'result' => $data];
    }


}