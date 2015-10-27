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
namespace Upadd\Bin;

use Upadd\Bin\UpaddException;
use Upadd\Bin\Rbac;

class Route
{

    public static $prefix = '';

    public static $_filters = '';

    public static $_resou = array();

    public static function __callstatic($method, $params)
    {
        $url = '';
        if(self::$prefix){
            $url = self::$prefix.$params[0];
        }else{
            $url = $params[0];
        }
        self::$_resou[sha1($url)] = array(
            'methods'=>strtoupper($method),
            'callbacks'=>$params[1],
            'url'=>$url,
            'filters'=>(self::$_filters ? self::$_filters : '')
        );
    }

    /**
     * 组
     * @param $method
     * @param $_callback
     * @param null $name
     */
    public static function group($method,$_callback,$name=null){
        if(isset($method['prefix'])) {
            self::$prefix = $method['prefix'];
        }

        if(isset($method['filters'])){
            self::$_filters = $method['filters'];
        }

        if(is_callable($_callback)) {
            call_user_func($_callback);
        }
    }


    public static function filters($key,$_callback){
        $_urlKey = self::getUrlKey();
        if(isset(self::$_resou[$_urlKey])){
            //获取本次请求路由资源
            $resou  = self::$_resou[$_urlKey];
            if($resou['filters'] == $key)
            {
                is_callable($_callback) && call_user_func($_callback);
            }
        }
    }

    /**
     * 处理路由请求参数
     */
    public static function setRewrite($_url=null){
        foreach(self::$_resou as $k=>$v){
            if($v['url'] != '/'){
                //获取路由长度
                $_routeInt = strlen($v['url']);
                $_urlInt = strlen($_url);
                $num = $_urlInt - $_routeInt;
                $param = substr($_url,-$num);
                $key =  substr($_url,0,-strlen($param));
                //有参数执行
                if($key && $key === $v['url']){
                    $val = preg_replace('/\/(\w+)\/([^\/]+)/', '\\1'.':'.'\\2'.',', $param);
                    $val = self::setParam($val);
                    return $v['url'];
                    //没参数执行
                }elseif($v['url'] === $_url)
                {
                    return $v['url'];
                }
            }
        }
    }


    /**
     * 设置请求参数
     * @param $spec
     * @param null $value
     * @return $this
     */
    private static function setParam($val)
    {
        $val = substr($val,0,-1);
        $val = lode(',',$val);
        $_data = array();
        foreach($val as $k=>$v){
            $tmpLode = lode(':',$v);
            if(count($tmpLode) == 2){
                list($_key,$value) = $tmpLode;
                $_GET[$_key] = $value;
            }
        }
    }


    /**
     * 获取URLkey
     * @return string
     */
    private static function getUrlKey(){
        $_pathUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if($_pathUrl == '/') {
            return sha1('/');
        }else{
            return sha1(self::setRewrite($_pathUrl));
        }
    }

    /**
     * 设置控制器
     * @throws UpaddException
     */
    public static function getAction(){
        $_key = self::getUrlKey();
        if(isset(self::$_resou[$_key])){
            $obj = self::$_resou[$_key];
            $_methods = $obj['methods'];
            $_callbacks = $obj['callbacks'];
            //获取控制器对象
            $_objAction = explode('@',$_callbacks);
            //判断控制器
            if(count($_objAction) < 2){
                throw new UpaddException('The controller set wrong..');
            }
            return $_objAction;
        }else{
            throw new UpaddException('路径不存在..');
        }
    }


    /**
     * 析构URL
     * @throws UpaddException
     */
    public static function dispatch()
    {
        try {
            list($_actionName,$functuion) = self::getAction();
            $controller = new $_actionName();
            //设置模板控制器
            $controller->setViewAction($_actionName);
            $controller->$functuion();
        } catch( UpaddException $e ) {
            echo $e->getMessage();
        }
    }



}