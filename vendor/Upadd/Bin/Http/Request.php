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

use Config;

class Request{

    /**
     * 命令行数据
     * @var array
     */
    public $_cliData = array();

    /**
     * 请求容器
     * @var array
     */
    public $_routing = array();

    /**
     * 实例化对象
     * @var null
     */
    public $_work = null;

    /**
     * 控制器
     * @var null
     */
    public $_action = null;

    /**
     * 方法
     * @var null
     */
    public $_method = null;


    /**
     * 入口
     * @param $argv
     */
    public function getInit($_work,$argv){
        $this->_work = $_work;
        $this->_cliData = getArgs($argv);
    }


    /**
     * 命令行模式
     */
    public function setCli(){
        $cli_action_autoload = Config::get('start@cli_action_autoload');
        if(isset($this->_cliData['u']) && isset($this->_cliData['p'])) {
            $this->getAction($cli_action_autoload . $this->_cliData['u'] . 'Action' . '@' . $this->_cliData['p']);
        }
        unset($this->_cliData['u']);
        unset($this->_cliData['p']);
        return $this->Instantiation();

    }

    /**
     * http模式
     * @throws UpaddException
     */
    public function setCgi()
    {
        $Route = $this->_work['Route'];
        $_pathUlr = $this->getUrlHash($Route->_resou);
        if(isset($Route->_resou[$_pathUlr]))
        {
            $this->_routing = $Route->_resou[$_pathUlr];

            if(is_callable($this->_routing['callbacks']))
            {
                return call_user_func_array($this->_routing['callbacks'],func_get_args());
            }

            $this->getAction($this->_routing['methods']);

            return $this->Instantiation();

        }else{
            throw new UpaddException('RouteMode error');
        }
    }


    /**
     * 获取加密后的URL
     * @return string
     */
    public function getUrlHash($_resou)
    {
        $_pathUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if($_pathUrl == '/')
        {
            return sha1('/');
        }else{
            return sha1($this->setRewrite($_pathUrl,$_resou));
        }
    }



    /**
     * 处理路由请求参数
     */
    public function setRewrite($_url=null,$_resou=array()){
        if(is_array($_resou)) {
            foreach ($_resou as $k => $v) {
                if ($v['url'] != '/') {
                    //获取路由长度
                    $_routeInt = strlen($v['url']);
                    $_urlInt = strlen($_url);
                    $num = $_urlInt - $_routeInt;
                    $param = substr($_url, -$num);
                    $key = substr($_url, 0, -strlen($param));
                    //有参数执行
                    if ($key && $key === $v['url']) {
                        $val = preg_replace('/\/(\w+)\/([^\/]+)/', '\\1' . ':' . '\\2' . ',', $param);
                        $this->getParam($val);
                        return $v['url'];
                        //没参数执行
                    } elseif ($v['url'] === $_url) {
                        return $v['url'];
                    }
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
    private function getParam($val)
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
     * 获取控制器
     * @throws UpaddException
     */
    public function getAction($methods){
        if($_objAction = explode('@',$methods)){
            return list($this->_action, $this->_method) = $_objAction;
        }
        throw new UpaddException('The Action set wrong..');
    }



    /**
     * 实例化
     */
    public function Instantiation()
    {
        try {

            if(class_exists($this->_action))
            {
                $class = new $this->_action();
                $method = $this->_method;
                if(!is_run_evn()){
                    return call_user_func_array(array($class,$method),$this->_cliData);
                }

                return call_user_func_array(array($class,$method),func_get_args());

            }else{
                throw new UpaddException($this->_action.',There is no Action');
            }

        } catch( UpaddException $e ) {
            if(is_run_evn()){
                p($e->getMessage());
            }else{
                print_r($e->getMessage());
            }
        }
    }



}