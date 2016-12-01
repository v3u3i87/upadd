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
namespace Upadd\Bin\Http;

use Config;
use Data;
use Upadd\Bin\UpaddException;
use Upadd\Bin\View\Error;


class Request
{

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
     * 响应类型
     * @var null
     */
    public $_responseType;


    public $_reqUrl = '/';


    /**
     * 获取实例化工作对象
     * @param $_work
     * @param $argv
     */
    public function getInit($_work, $argv)
    {
        $this->_work = $_work;
        $this->_cliData = getArgs($argv);
    }


    /**
     * 命令行模式
     */
    public function run_cli()
    {
        $cli_action_autoload = Config::get('start@cli_action_autoload');
        if (array_key_exists('u', $this->_cliData) && array_key_exists('p', $this->_cliData)) {
            $this->getAction($cli_action_autoload . ucfirst($this->_cliData['u']) . 'Action' . '@' . $this->_cliData['p']);
        }
        unset($this->_cliData['u']);
        unset($this->_cliData['p']);
        Data::accept($this->_cliData);
        return $this->Instantiation();
    }

    /**
     * http模式
     * @throws UpaddException
     */
    public function run_cgi()
    {
        if (APP_ROUTES)
        {
            $this->_routing = $this->getRoute()->resources();
            if (is_callable($this->_routing['callbacks']))
            {
                return call_user_func_array($this->_routing['callbacks'], func_get_args());
            }
            $this->getAction($this->_routing['methods']);

        } else {
            $set_action = Data::get(Config::get('sys@set_action'));
            $set_function = Data::get(Config::get('sys@set_function'));
            $action = $set_action ? $set_action : 'index';
            $action && $action = ucfirst($action);
            $this->_action = APP_NAME . "\\action\\{$action}Action";
            $this->_method = $set_function ? $set_function : 'main';
        }
        return $this->Instantiation();
    }

    /**
     * 获取当前的URL
     * @return mixed
     */
    public function getPathUrl()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * 获取请求类型
     * @return mixed
     */
    protected function getRequestMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * 设置URL哈希加密
     * @return string
     */
    public function setUrlHash()
    {
        return $this->getPathUrl();
    }


    /**
     * 处理路由请求参数
     * @param $setResou
     * @return string
     */
    public function setRewrite($setResou, $currentRequest)
    {

        if ($setResou == '/') {
            return false;
        }

        $regex = '@' . $setResou . '((?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
        if (preg_match($regex, $currentRequest, $m)) {
            $arr = explode('/', $m[1]);
            $arr = array_associated_index($arr);
            $arr && Data::accept($arr);
            return $setResou;
        }
        return false;

    }

    /**
     * 设置请求参数
     * @param      $spec
     * @param null $value
     * @return $this
     */
    private function setGetParam($val)
    {
        $val = substr($val, 0, -1);
        $val = lode(',', $val);
        foreach ($val as $k => $v) {
            $tmpLode = lode(':', $v);
            if (count($tmpLode) == 2) {
                list($_key, $value) = $tmpLode;
                Data::accept([$_key => $value]);
            }
        }
    }

    /**
     * 获取路由
     * @return mixed
     * @throws UpaddException
     */
    public function getRoute()
    {
        if (isset($this->_work['Route'])) {
            return $this->_work['Route'];
        }
        throw new UpaddException('请求获取路由失败');
    }

    /**
     * 获取控制器
     * @throws UpaddException
     */
    public function getAction($methods=null)
    {
        if ($methods)
        {
            $_objAction = explode('@', $methods);
            //路由设置控制器
            $this->getRoute()->setAction($_objAction[0], $_objAction[1]);
            return list($this->_action, $this->_method) = $_objAction;
        }
        throw new UpaddException('The Action set wrong..');
    }


    /**
     * Instantiation Action
     * @return mixed
     */
    public function Instantiation()
    {
        try {
            if (class_exists($this->_action))
            {
                Config::setFileVal('sys', 'request', ['action' => $this->_action, 'method' => $this->_method]);
                /**
                 * 实例化控制器
                 */
                $class = new $this->_action();

                /**
                 * 获取方法
                 */
                $method = $this->_method;

                $tmpData = null;

                if (is_run_evn()) {
                    $class->init();
                    $tmpData = func_get_args();
                } else {
                    $tmpData = $this->_cliData;
                }

                $result = call_user_func_array(array($class, $method), $tmpData);

                $this->_responseType = $class->getResponseType();

                return $result;
            } else {
                throw new UpaddException('There is no Action');
            }

        } catch (UpaddException $e)
        {
            if (is_run_evn())
            {
                if(Config::get('tag@is_http_url_error'))
                {
                    Error::html();
                }else{
                    throw new UpaddException($e->getMessage());
                }
            } else {
                throw new UpaddException($e->getMessage());
            }
        }

    }


}