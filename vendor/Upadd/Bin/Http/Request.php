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

class Request{

    public $_cliData = array();

    public $_routing = array();

    public $methods = null;

    public $_work = null;


    /**
     * 入口
     * @param $argv
     */
    public function init($_work,$argv){
        $this->_work = $_work;
        if($this->is_run_evn()){
            $this->setRouteMode();
        }else{
            $this->_cliData = getArgs($argv);
            $this->setCliMethods();
        }
    }


    public function setCliMethods(){
        $start = $this->_work['Configuration']->_config['start'];
        $cli_action_autoload = $start['cli_action_autoload'];
        if(isset($this->_cliData['u']) && isset($this->_cliData['p'])) {
            $this->methods = $cli_action_autoload . $this->_cliData['u'] . 'Action' . '@' . $this->_cliData['p'];
        }
        $this->run();
    }

    public function setRouteMode(){
        $Route = $this->_work['Route'];
        //导入URL资源
        $_pathUlr = $this->getUrlHash($Route->_resou);
        if(isset($Route->_resou[$_pathUlr])){
            $this->_routing = $Route->_resou[$_pathUlr];
            if(is_callable($this->_routing['callbacks'])){
                call_user_func_array($this->_routing['callbacks'],func_get_args());
            }else{
                $this->methods = $this->_routing['methods'];
                $this->run();
            }
        }else{
            throw new UpaddException('RouteMode error');
        }
    }


    /**
     * 获取加密后的URL
     * @return string
     */
    public function getUrlHash($_resou){
        $_pathUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if($_pathUrl == '/') {
            return sha1('/');
        }else{
            return sha1($this->setRewrite($_pathUrl,$_resou));
        }
    }


    /**
     * 判断PHP执行环境
     * @return bool
     */
    public function is_run_evn(){
        if(php_sapi_name() === 'cli'){
            return false;
        }elseif(PHP_SAPI === 'cli'){
            return false;
        }else{
            return true;
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
    public function getAction(){
        if($_objAction = explode('@',$this->methods)){
            return $_objAction;
        }
        throw new UpaddException('The controller set wrong..');
    }

    /**
     * 运行控制器
     */
    public function run(){
        try {
            list($_class,$functuion) = $this->getAction();
            if(class_exists($_class)){
                $controller = new $_class();
                //设置模板控制器
                $controller->setViewAction($_class);
                $controller->$functuion();
            }else{
                throw new UpaddException($_class.',There is no Action');
            }
        } catch( UpaddException $e ) {
            if($this->is_run_evn()){
                p($e->getMessage());
            }else{
                print_r($e->getMessage());
            }
        }
    }


    public function analysis_parameters(){}

}