<?php

namespace Upadd\Bin;


use Upadd\Bin\Grab;
use Upadd\Bin\Factory;
use Upadd\Bin\Loader;
use Upadd\Bin\Di;
use Upadd\Bin\Config\Configuration;
use Upadd\Bin\Http\Dispenser;


class Application
{

    /**
     * 配置文件
     * @var array
     */
    private $configMap = [];

    /**
     * 派发器
     * @var
     */
    private $dispenser;


    private static $init = null;


    /**
     * @return Application
     */
    public static function init()
    {
        if (self::$init === null) {
            self::$init = new static();
//            p(self::$init, true);
            return self::$init;
        }
    }


    /**
     * Application constructor.
     */
    final private function __construct()
    {
        $this->loadConfig();
        $this->loadWorks();
        $this->loadAppWorksFiles();
        $this->loadFactory();
        $this->loadAlias()->run();
        Grab::run();
        $this->loadSession();
//        $this->loadSystemWorkConfig();
        $this->loadRoute();
        $this->loadInitConfig();
    }


    private function loadConfig()
    {
        $this->configMap = Di::set('Configuration', new \Upadd\Bin\Config\Configuration)->getConfigLoad();
        Di::setConfigData($this->configMap);
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
            \Upadd\Bin\Session\Load::setFileType($this->configMap['sys']['session']);
        }
    }

    /**
     * 实例化全局工作模块
     * @param $work
     */
    private function loadWorks()
    {
        Di::import([
            'GetConfiguration' => new \Upadd\Bin\Config\GetConfiguration,
            'Request' => new \Upadd\Bin\Http\Request,
            'Route' => new \Upadd\Bin\Http\Route,
            'getSession' => \Upadd\Bin\Session\getSession::init(),
            'Log' => new \Upadd\Bin\Tool\Log,
            'Data' => new \Upadd\Bin\Http\Data,
        ]);
    }


    private function loadAppWorksFiles()
    {
        return Loader::execute();
    }

    private function loadFactory()
    {
        return Factory::Import(Di::all());
    }


    /**
     * 获取别名
     * @return \Upadd\Bin\Alias
     * @throws \Upadd\Bin\UpaddException
     */
    private function loadAlias()
    {
        return (new Alias($this->configMap));
    }


    /**
     * 全局配置工作
     * @return array
     */
    private function loadSystemWorkConfig()
    {
        $this->configMap['sys'] = (array_merge($this->configMap['sys']));
    }


    /**
     * 把路由加载到请求器
     */
    private function loadRoute()
    {
        $route = Di::get('Route');
        $route->getRequest(Di::get('Request'));
    }

    /**
     * php
     * 获取Session配置状态
     * @return mixed
     */
    private function getSessionStatus()
    {
        return ($this->configMap['sys']['is_session']);
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


}