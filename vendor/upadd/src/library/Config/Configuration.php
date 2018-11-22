<?php

namespace Upadd\Bin\Config;

use Upadd\Bin\UpaddException;

class Configuration
{

    private $hostName = null;

    private $_evn = null;

    private static $_configData = [];

    /**
     * 加载配置文件
     * @return array|bool
     */
    public function getConfigLoad()
    {
        $conf = [];
        $conf['sys'] = $this->mergeConfig();
        $this->hostName = gethostname();
        if (array_key_exists('environment', $conf['sys'])) {
            $evn = $conf['sys']['environment'];
            $this->_evn = $this->getEvnName($evn);
        }
        $configPath = host() . '/config';
        if ($this->_evn) {
            //获取配置目录的所有文件
            $config = $this->getConfigName($configPath . '/' . $this->_evn);
            if ($config) {
                $conf = array_merge($conf, $config);
            }
        } else {
            $conf['database'] = $this->getConfigName($configPath . '/' . 'database.php', false);
        }
        return (static::$_configData = $conf);
    }


    /**
     * 获取配置文件
     * @param $configPath
     * @return array|bool
     */
    public function getConfigName($configPath, $type = true)
    {
        if ($type) {
            return $this->soFileLoad($configPath);
        }
        if (file_exists($configPath)) {
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
        if ($file = scandir($configPath)) {
            if (count($file) >= 2) {
                $_config = array();
                foreach ($file as $k => $v) {
                    if ($k >= 2) {
                        $fileName = $configPath . '/' . $v;
                        //判断文件是否存在
                        if (file_exists($fileName)) {
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
    public function getConfLoad($fileNmae = null)
    {
        try {

            $file = host() . '/config/' . $fileNmae . '.php';

            if (file_exists($file) && is_file($file)) {
                return require $file;
            }
        } catch (\Exception $e) {
            throw new UpaddException($e->getMessage());
        }
    }

    /**
     *  获取环境名称
     * @param array $env
     * @return bool|int|string
     */
    public function getEvnName($env = array())
    {
        if ($env) {
            foreach ($env as $k => $v) {
                if (is_array($v)) {
                    if (in_array($this->hostName, $v)) {
                        return $k;
                    }
                } else {
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
        return (require host() . VENDOR . '/src/public/config.php');
    }

    /**
     * 获取启动配置
     * @return mixed
     */
    protected function getStart()
    {
        return (require host() . '/config/start.php');
    }

    /**
     * 合并配置文件
     * @return array
     */
    protected function mergeConfig()
    {
        return array_merge($this->getStart(), $this->getSys());
    }

    /**
     * 获取参数
     * @param $key
     * @return bool
     */
    public static function get($key)
    {
        if (list($_key, $val) = lode('@', $key)) {
            if (isset(static::$_configData[$_key][$val])) {
                return static::$_configData[$_key][$val];
            }
        }
        return false;
    }

}