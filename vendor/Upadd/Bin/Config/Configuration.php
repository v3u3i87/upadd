<?php

namespace Upadd\Bin\Config;

use Upadd\Bin\UpaddException;

class Configuration{

    public $_config = array();

    //电脑名称
    public $hostName = null;

    public $_evn = null;

    public $_sys = [];

    public static $_configData = array();

    /**
     * 加载配置文件
     * @return array|bool
     */
    public function getConfigLoad()
    {
        $this->_sys = $this->mergeConfig();
        $this->_config['sys'] = $this->_sys;
        $this->hostName = gethostname();
        if(array_key_exists('environment',$this->_sys))
        {
            $evn = $this->_sys['environment'];
            $this->_evn = $this->getEvnName($evn);
        }
        $configPath = host().'config';
        if($this->_evn)
        {
            //获取配置目录的所有文件
            $config = $this->getConfigName($configPath.'/'.$this->_evn );
            if($config)
            {
                $this->_config = array_merge($this->_config,$config);
            }
        }else{
            $this->_config['database'] = $this->getConfigName($configPath.'/'.'database.php',false);
        }

        static::$_configData = $this->_config;
        return $this->_config;
    }

    /**
     * 获取配置文件
     * @param $configPath
     * @return array|bool
     */
    public function getConfigName($configPath,$type=true)
    {
        if($type)
        {
           return $this->soFileLoad($configPath);
        }

        if(file_exists($configPath))
        {
            return (require $configPath);
        }
        return false;
    }

    /**
     * 匹配
     * @param $configPath
     * @return array
     */
    protected function soFileLoad($configPath)
    {
        if($file = scandir($configPath))
        {
            if(count($file) >= 2)
            {
                $_config = array();
                foreach ($file as $k => $v)
                {
                    if ($k >= 2)
                    {
                        $fileName = $configPath.'/'.$v;
                        //判断文件是否存在
                        if(file_exists($fileName))
                        {
                            $tmpFile = require $fileName;
                            $_config[substr($v, 0, -4)] = $tmpFile;
                        }

                    }
                }
                return $_config;
            }
        }
        return false;
    }

    /**
     * 获取配置文件
     * @param string $fileNmae
     */
    public function getConfLoad($fileNmae=null)
    {
        try {

            $file = host().'config/'.$fileNmae.'.php';

            if(file_exists($file) && is_file($file))
            {
                return require $file;
            }
        }catch (\Exception $e)
        {
            p($e->getMessage());
        }
    }

    /**
     *  获取环境名称
     * @param array $env
     * @return bool|int|string
     */
    public function getEvnName($env = array())
    {
        if($env)
        {
            foreach ($env as $k => $v)
            {
                if(is_array($v))
                {
                    if (in_array($this->hostName, $v))
                    {
                        return $k;
                    }
                }else{
                    return $k;
                }
            }
        }
        return false;
    }

    /**
     * 获取系统配置
     * @return mixed
     */
    protected function getSys()
    {
        $sys = host().VENDOR.'/Public/config.php';
        return (require $sys);
    }

    /**
     * 获取启动配置
     * @return mixed
     */
    protected function getStart()
    {
        $start = host().'/config/start.php';
        return (require $start);
    }

    /**
     * 合并配置文件
     * @return array
     */
    protected function mergeConfig()
    {
        return array_merge($this->getStart(),$this->getSys());
    }

    /**
     * 获取参数
     * @param $key
     * @return bool
     */
    public static function get($key)
    {
        if(list($_key,$val) = lode('@',$key))
        {
            if(isset(static::$_configData[$_key][$val]))
            {
                return static::$_configData[$_key][$val];
            }
        }
        return false;
    }

}