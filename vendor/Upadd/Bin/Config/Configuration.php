<?php

namespace Upadd\Bin\Config;

class Configuration{

    public $_config = array();

    public $start = array();

    //电脑名称
    public $hostName = null;

    public $_evn = null;

    public static $_configData = array();

    public function getConfigLoad(){
        $this->start = $this->getConfLoad();
        $this->hostName = gethostname();
        $evn = $this->start['environment'];
        $this->_evn = $this->getEvnName($evn);
        $configPath = host().'config';
        if($this->_evn ){
            $config = $this->getConfigName($configPath.'/'.$this->_evn );
            if($config){
                $this->_config = $config;
            }
        }else{
            $this->_config['database'] = $this->getConfigName($configPath.'/'.'database.php');
        }
        $this->_config['start'] = $this->start;
        $this->_config['sys'] = $this->sysConfig();
        static::$_configData = $this->_config;
        return $this->_config;
    }

    public function getConfigName($configPath){
        if($file = scandir($configPath)){
            if(count($file) >= 2) {
                $_config = array();
                foreach ($file as $k => $v) {
                    if ($k >= 2) {
                        if($tmpFile = require $configPath.'/'.$v){
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
    public function getConfLoad($fileNmae='')
    {
        try {
            $file = 'start.php';
            if($fileNmae){
                $file = $fileNmae;
            }
            $file = host().'config/'.$file;
            if(file_exists($file)) return require $file;
        }catch (\Exception $e){
            p($e->getMessage());
        }
    }

    /**
     *  获取环境名称
     * @param array $env
     * @return bool|int|string
     */
    public function getEvnName($env = array()){
        if(!empty($env)) {
            foreach ($env as $k => $v) {
                if (in_array($this->hostName, $v)) {
                    return $k;
                    break;
                }
            }
        }
        return false;
    }

    //系统配置文件
    protected function sysConfig(){
        return require host().VENDOR.'/Public/config.php';
    }

    public static function get($key){
        if(list($_key,$val) = lode('@',$key)){
            if(isset(static::$_configData[$_key][$val])) {
                return static::$_configData[$_key][$val];
            }
        }
        return false;
    }

}