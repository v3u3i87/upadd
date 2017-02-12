<?php
namespace Upadd\Bin\Http;

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 2017/1/19
 * Time: 下午5:27
 * Name: 派发器
 */

use Config;
use Data;
use Log;
use Upadd\Bin\Response\Execute as response;
use Upadd\Bin\View\Prompt;
use Upadd\Bin\UpaddException;

class Dispenser
{

    private $_work = null;

    /**
     * 命令行数据
     * @var array
     */
    public $_cliData = array();

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
     * @var string
     */
    protected $_responseType = 'json';

    protected $_responseCode = 200;

    protected $_responseHeader = [];

    /**
     * 相应数据
     * @var
     */
    private $_responseData;


    public function __construct()
    {
        $this->_work = Config::get('sys@work');
    }

    /**
     * fpm模式
     * @param array $argv
     */
    public function fpm()
    {
        $request = $this->_work['Request'];
        $request->header = array_change_key_case(getHeader());
        $request->server = array_change_key_case($_SERVER);
        //日志
        $request->setRequestLog();
        $this->_responseData = $this->http();
        $response = new response();
        $response->type = $this->_responseType;
        $response->code = $this->_responseCode;
        $response->header = $this->_responseHeader;
        $response->content = $this->_responseData;
        echo $response->sendHttp();
    }

    /**
     * 命令行模式
     * @param array $argv
     */
    public function console()
    {
        $argv = Config::get('sys@argv');
        if ($this->_cliData = getArgs($argv))
        {
            $this->_responseData = $this->command();
            $response = new response();
            $response->type = $this->_responseType;
            $response->code = $this->_responseCode;
            $response->header = $this->_responseHeader;
            return $this->_responseData;
        }else{
            throw new UpaddException('console Cant get the data');
        }
    }

    /**
     * swoole 使用
     * @param array $swoole_http_request
     * @return null
     */
    public function swoole($swoole_http_request)
    {
        $params = [];
        if (isset($swoole_http_request->get)) {
            $params = $swoole_http_request->get;
        }

        if (isset($swoole_http_request->post)) {
            if (count($params) >= 1) {
                $params = array_merge($params, $swoole_http_request->post);
            } else {
                $params = $swoole_http_request->post;
            }
        }

        if(isset($swoole_http_request->files)){
            if (count($params) >= 1) {
                $params = array_merge($params, $swoole_http_request->files);
            } else {
                $params = $swoole_http_request->files;
            }
        }

        $rawContent = $swoole_http_request->rawContent();
        if($rawContent){
            Data::setStream($rawContent);
        }
        Data::accept($params);
        $request = $this->_work['Request'];
        $request->header = isset($swoole_http_request->header) ? $swoole_http_request->header : [];
        $request->server = isset($swoole_http_request->server) ? $swoole_http_request->server : [];
        //过滤浏览器自动请求  favicon.ico
        if ($request->server['request_uri'] !== '/favicon.ico') {
            //设置请求日志
            $request->setRequestLog();
            $this->_responseData = $this->http();
            $response = new response();
            $response->type = $this->_responseType;
            $response->code = $this->_responseCode;
            $response->header = $this->_responseHeader;
            $response->content = $this->_responseData;
            return $response->sendSwooleHtpp();
        }
    }


    /**
     * 获取控制器
     * @throws UpaddException
     */
    public function getAction($methods = null)
    {
        if ($methods) {
            $_objAction = explode('@', $methods);
            //路由设置控制器
            $this->getRoute()->setAction($_objAction[0], $_objAction[1]);
            return (list($this->_action, $this->_method) = $_objAction);
        }
        throw new UpaddException('The Action set wrong..');
    }

    /**
     * http模式 run_cgi
     * @throws UpaddException
     */
    public function http()
    {
        if (APP_ROUTES) {
            $_routing = $this->getRoute()->resources();
            if (is_callable($_routing['callbacks'])) {
                return call_user_func_array($_routing['callbacks'], func_get_args());
            }

            if ($_routing['methods']) {
                $this->getAction($_routing['methods']);
            }

        } else {
            $set_action = Data::get(Config::get('sys@set_action'));
            $set_function = Data::get(Config::get('sys@set_function'));
            $action = $set_action ? $set_action : 'index';
            $action && $action = ucfirst($action);
            $this->_action = APP_NAME . "\\action\\{$action}Action";
            $this->_method = $set_function ? $set_function : 'main';
        }
        return $this->instantiation();
    }


    /**
     * 命令行模式
     * run_cli
     */
    public function command()
    {
        $cli_action_autoload = Config::get('start@cli_action_autoload');
        if (array_key_exists('u', $this->_cliData) && array_key_exists('p', $this->_cliData)) {
            $this->getAction($cli_action_autoload . ucfirst($this->_cliData['u']) . 'Action' . '@' . $this->_cliData['p']);
        }
        unset($this->_cliData['u']);
        unset($this->_cliData['p']);
        Data::accept($this->_cliData);
        return $this->instantiation();
    }


    /**
     * Instantiation Action
     * @return mixed
     */
    public function instantiation()
    {
        if (class_exists($this->_action)) {
            Config::setFileVal('sys', 'request', ['action' => $this->_action, 'method' => $this->_method]);
            $class = new $this->_action();
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
            $this->_responseHeader = $class->getResponseHeader();
            $this->getResponseCode = $class->getResponseCode();
            return $result;
        } else {
            throw new UpaddException('There is no Action');
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


}