<?php

namespace Upadd\Bin;


use Upadd\Bin\Grab;
use Upadd\Bin\Factory;
use Upadd\Bin\Loader;

use Upadd\Bin\Config\Configuration;
use Upadd\Bin\Http\Dispenser;


class Application
{

    /**
     * 配置文件
     * @var array
     */
    private static $_config = [];

    /**
     * 初始化组件对象
     * @var array
     */
    private $app = [];

    /**
     * 派发器
     * @var
     */
    private $dispenser;


    /**
     * @return Application
     */
    public static function init()
    {
        return new static();
    }


    /**
     * Application constructor.
     */
    final private function __construct()
    {
        $this->loadConfig();
        $this->loadSession();
        $this->loadWorks();
        $this->loadAppWorksFiles();
        $this->loadFactory();
        $this->loadAlias()->run();
        Grab::run();
        $this->loadSystemWorkConfig();
        $this->loadRoute();
        $this->loadInitConfig();
    }

    /**
     * 获取配置文件
     */
    private function loadConfig()
    {
        static::$_config = $this->getConfiguration()->getConfigLoad();
    }

    private function loadInitConfig()
    {
        $hostConfigPath = host() . '/config';
        /**
         * 扩展文件
         */
        $extend = $hostConfigPath . '/extend.php';
        file_exists($extend) && require $extend;
        /**
         * 路由配置
         */
        $routing = $hostConfigPath . '/routing.php';
        file_exists($routing) && require $routing;
        /**
         * 过滤器
         */
        $filters = $hostConfigPath . '/filters.php';
        file_exists($filters) && require $filters;
    }

    /**
     * load session
     * @return bool
     */
    private function loadSession()
    {
        if ($this->getSessionStatus()) {
            \Upadd\Bin\Session\Load::setFileType(static::$_config['sys']['session']);
        }
    }

    /**
     * 实例化全局工作模块
     * @param $work
     */
    private function loadWorks()
    {
        $this->app = [
            'GetConfiguration' => new \Upadd\Bin\Config\GetConfiguration,
            'Request' => new \Upadd\Bin\Http\Request,
            'Route' => new \Upadd\Bin\Http\Route,
            'getSession' => \Upadd\Bin\Session\getSession::init(),
            'Log' => new \Upadd\Bin\Tool\Log,
            'Data' => new \Upadd\Bin\Http\Data,
        ];
    }


    private function loadAppWorksFiles()
    {
        return Loader::execute();
    }

    private function loadFactory()
    {
        return Factory::Import($this->app);
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
    private function loadAlias()
    {
        return (new Alias(static::$_config));
    }


    /**
     * 全局配置工作
     * @return array
     */
    private function loadSystemWorkConfig()
    {
        static::$_config['sys'] = (array_merge(static::$_config['sys'], ['app' => $this->app]));
    }


    /**
     * 把路由加载到请求器
     */
    private function loadRoute()
    {
        $route = $this->app['Route'];
        $route->getRequest($this->app['Request']);
    }

    /**
     * php
     * 获取Session配置状态
     * @return mixed
     */
    private function getSessionStatus()
    {
        return (static::$_config['sys']['is_session']);
    }


    /**
     * @return mixed
     */
    public function loadCig()
    {
        return $this->dispenser->fpm();
    }

    /**
     * @param array $argv
     * @return mixed
     */
    public function loadConsole($argv = [])
    {
        return $this->dispenser->console($argv);
    }


    /**
     * @param $callable
     * @param array $argv
     */
    public function execute()
    {
        date_default_timezone_set('Asia/Shanghai');
        $this->dispenser = new Dispenser();
    }

//
//    /**
//     * @param $callable
//     * @param array $argv
//     */
//    public function run($callable)
//    {
//        date_default_timezone_set('Asia/Shanghai');
//        if (is_callable($callable)) {
//            call_user_func_array($callable, func_get_args());
//        }
//        $this->dispenser = new Dispenser();
//    }


    /**
     * @param string $name
     * @return array|mixed|null
     */
    public function getWorks($name = '')
    {
        if ($name) {
            return isset($this->app[$name]) ? $this->app[$name] : null;
        } else {
            return $this->app;
        }
    }

}