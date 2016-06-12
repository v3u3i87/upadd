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

class Route extends Request{

    public $prefix = '';

    public $filters = '';

    public $_resou = array();

    public static $action = null;

    public static $method = null;

    public $_tmp = array();

    /**
     * URL组
     * @param array $method
     * @param $_callback
     */
    public function group($method=array(),$_callback)
    {
        if(array_key_exists('prefix',$method))
        {
            $this->prefix = $method['prefix'];
        }

        if(array_key_exists('filters',$method))
        {
            $this->filters = $method['filters'];
        }

        if(is_callable($_callback))
        {
           return call_user_func($_callback);
        }

    }


    /**
     * 过滤器
     * @param $key
     * @param $_callback
     */
    public function filters($key,$_callback)
    {
        $_urlKey = $this->setUrlHash();
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


    /**
     * 判断方法模式
     * @param $method
     * @return callable|null|string
     */
    public function is_method($method, $callable = null)
    {
        if(is_callable($method))
        {
            $callable = $method;
        }elseif(is_string($method))
        {
            $callable = $method;
        }
        return $callable;
    }


    /**
     * 设置资源
     * @param $url
     * @param $method
     * @param $type
     */
    public function setResou($url='',$method=null,$type='')
    {
        $method = $this->is_method($method);
        $sha_url = sha1($this->is_url($url));
        $this->_resou[$sha_url] = array(
            'methods'=>$method,
            'callbacks'=>$method,
            'url'=>$url,
            'type'=>$type,
        );
        $this->_resou[$sha_url]['prefix'] = $this->prefix;
        $this->_resou[$sha_url]['filters'] = $this->filters;
    }

    /**
     * 判断URL
     * @param $url
     * @return string
     */
    public function is_url($url)
    {
        if($this->getPathUrl() === $url)
        {
           return $url;
        }else{
            $url = $this->prefix.$url;
        }
        return $url;
    }

    /**
     * 获取资源
     * @return bool || array
     */
    public function getResou()
    {
         if(array_key_exists($this->setUrlHash(),$this->_resou))
         {
            return $this->_resou[$this->setUrlHash()];
         }
         return false;
    }

    /**
     * 获取控制器
     * @param null $_action
     * @param null $_method
     */
    public function setAction($_action=null,$_method=null)
    {
        if($_action && $_method)
        {
            static::$action = $_action;
            static::$method = $_method;
        }
    }


    /**
     * GET方法,从服务器上获取一个具体的资源或者一个资源列表
     * @param null $getUrl
     * @param null $method
     */
    public function get($getUrl='',$method=null)
    {
        return $this->setResou($getUrl,$method,'GET');
    }

    /**
     * POST方法 在服务器上创建一个新的资源
     * @param string $getUrl
     * @param null $method
     */
    public function post($getUrl='',$method=null)
    {
        return $this->setResou($getUrl,$method,'POST');
    }

    /**
     * 以整体的方式更新服务器上的一个资源
     */
    public function put(){}

    /**
     * 更新上一个资源的一个属性
     */
    public function patch(){}

    /**
     * 删除一个资源
     */
    public function delete(){}

    /**
     * 获取一个资源的元数据，如数据的哈希值或最后的更新时间
     */
    public function head(){}

    /**
     * 获取客户端能对资源做什么操作的信息
     */
    public function options(){}


}