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
namespace Upadd\Bin\Http;

use Upadd\Bin\UpaddException;
use Upadd\Bin\Tool\Rbac;

class Route extends Request{

    public $prefix = array();

    public $filters = array();

    public $_resou = array();

    /**
     * set group
     * @param array $method
     * @param $_callback
     */
    public function group($method=array(),$_callback){
        if(isset($method['prefix'])) {
            $this->prefix = $method['prefix'];
        }

        if(isset($method['filters'])){
            $this->filters = $method['filters'];
        }

        if(is_callable($_callback)) {
            call_user_func($_callback);
        }
    }



    public function filters($key,$_callback)
    {
        $_urlKey = $this->getUrlHash($this->_resou);
        if(isset($this->_resou[$_urlKey]))
        {
            //获取本次请求路由资源
            $resou  = $this->_resou[$_urlKey];
            if($resou['filters'] == $key)
            {
                is_callable($_callback) && call_user_func($_callback);
            }
        }
    }


    public function getMethod($method){
        $callable = null;
        if(is_callable($method)){
            $callable = $method;
        }elseif(is_string($method)){
            $callable = $method;
        }
        return $callable;
    }

    /**
     * get
     * @param null $getUrl
     * @param null $method
     */
    public function get($getUrl=null,$method=null){
        if($this->prefix){
            $getUrl = $this->prefix.$getUrl;
        }
        $this->setResou($getUrl,$method,'GET');
    }


    public function post($getUrl=null,$method=null){
        if($this->prefix){
            $getUrl = $this->prefix.$getUrl;
        }
        $this->setResou($getUrl,$method,'POST');
    }


    /**
     * 设置参数
     * @param $url
     * @param $method
     * @param $type
     */
    public function setResou($url,$method,$type){
        $this->_resou[sha1($url)] = array(
            'methods'=>$this->getMethod($method),
            'callbacks'=>$this->getMethod($method),
            'url'=>$url,
            'filters'=>$this->filters,
            'type'=>$type
        );
    }


}