<?php

namespace Upadd\Bin;

use Upadd\Bin\Config\Configuration;
use Upadd\Bin\Http\Dispenser;
use Config;


class Application
{

    /**
     * 配置文件
     * @var array
     */
    public static $_config = [];

    /**
     * 初始化组件对象
     * @var array
     */
    public $_work = [];

    /**
     * 派发器
     * @var
     */
    public $dispenser;


    /**
     * @param $callable
     * @param array $argv
     */
    public function run($callable)
    {
        date_default_timezone_set('Asia/Shanghai');
        if (is_callable($callable)) {
            call_user_func_array($callable, func_get_args());
        }
        $this->dispenser = new Dispenser();
    }

    /**
     * 实例化全局工作模块
     * @param $work
     */
    public function runWorkModule()
    {
        $di = new \Upadd\Bin\Di;
        $app = [
            'GetConfiguration' => new \Upadd\Bin\Config\GetConfiguration,
            'Request' => new \Upadd\Bin\Http\Request,
            'Route' => new \Upadd\Bin\Http\Route,
            'getSession' => \Upadd\Bin\Session\getSession::init(),
            'Log' => new \Upadd\Bin\Tool\Log,
            'Data' => new \Upadd\Bin\Http\Data,
//            'Cache'=>new \Upadd\Bin\Cache,
//            'Async'=>new \Upadd\Bin\Async,
        ];
        $di->import($app);
        $app['Di'] = $di;
        return ($this->_work = $app);
    }


    /**
     * 获取配置文件
     */
    public function loadConfig()
    {
        return (static::$_config = $this->getConfiguration()->getConfigLoad());
    }

    /**
     * 实例化全局配置文件
     * @return Configuration
     */
    protected function getConfiguration()
    {
        return ($this->_work['Configuration'] = new Configuration());
    }

    /**
     * 获取别名
     * @return \Upadd\Bin\Alias
     * @throws \Upadd\Bin\UpaddException
     */
    public function getAlias()
    {
        return (new Alias(static::$_config));
    }


    /**
     * 全局配置工作
     * @return array
     */
    public function setWorkConfig()
    {
        static::$_config['sys'] = array_merge(static::$_config['sys'], ['work' => $this->_work]);
    }


    /**
     * 把路由加载到请求器
     */
    public function setRoute()
    {
        $route = $this->_work['Route'];
        $route->getRequest($this->_work['Request']);
    }

    /**
     * php
     * 获取Session配置状态
     * @return mixed
     */
    private function getSessionStatus()
    {
        return static::$_config['sys']['is_session'];
    }

    /**
     * 设置 session
     * @return bool
     */
    public function setSession()
    {
        if ($this->getSessionStatus())
        {
            $config = static::$_config['sys']['session'];
            if ($config['domain']) {
                ini_set('session.cookie_domain', $config['domain']);
            }
            if ($config['expire']) {
                ini_set('session.gc_maxlifetime', $config['expire']);
                ini_set('session.cookie_lifetime', $config['expire']);
            }
            if ($config['use_cookies']) {
                ini_set('session.use_cookies', $config['use_cookies'] ? 1 : 0);
            }
            if ($config['cache_limiter']) {
                session_cache_limiter($config['cache_limiter']);
            }
            if ($config['cache_expire']) {
                session_cache_expire($config['cache_expire']);
            }

            $seeion = new \Upadd\Bin\Session\SessionFile();
            session_set_save_handler(
                array($seeion, 'open'),
                array($seeion, 'close'),
                array($seeion, 'read'),
                array($seeion, 'write'),
                array($seeion, 'destroy'),
                array($seeion, 'gc')
            );
            session_start();
        }
    }

}