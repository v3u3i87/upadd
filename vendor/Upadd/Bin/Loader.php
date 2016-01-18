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
namespace Upadd\Bin;

use Upadd\Bin\Tool\Log;
use Config;

class Loader
{
    /**
     * 自动加载对外拓展对象
     * @var array
     */
    private static $_autoload = array();

    private $_config = array();

    public static function Run()
    {
        self::loadDir();

        spl_autoload_register (function($className)
        {

            /**
             * 判断是否开启自定义
             * 默认不开启
             */
            if(Config::get('start@is_autoload'))
            {
                $autoload  = self::getAutoload();
                $className = lode("\\",$className);
                $className =  end($className);
                foreach($autoload as $k=>$v)
                {
                    $_filePath = UPADD_HOST.$v.$className.'.php';
                    if(is_file($_filePath)) break;
                }
            }else{
                $_filePath =  UPADD_HOST . str_replace('\\', '/', $className).'.php';
            }

            if(is_file($_filePath))
            {
                require $_filePath;

            }else{
                throw new UpaddException($_filePath.'文件不存在..');
            }

        });

    }


    /**
     * 获取命名空间规则
     * @return string
     */
    public static function getAutoload()
    {
        $autoload = Config::get('start@autoload');
        //判断是否启用插件
        if(Config::get('start@IS_PLUG')){
            $autoload = array_merge($autoload,self::$_autoload);
        }
        return $autoload;
    }

    /**
     * 设置加载的命名空间规则
     * @param $_autoloadArray
     */
    private static function setAutoload($_autoloadArray)
    {
        if($_autoloadArray){
            self::$_autoload = $_autoloadArray;
        }
    }



    /**
     * 判断运行环境
     * @return bool
     */
    private static function isRunMachineName(){
        $env = Config::get('start@environment');
        //merge in config array
        $oneEnv = array_merge_one($env);
        $osName = getMachineName();
        if(in_array($osName,$oneEnv)){
            $configDir = Config::get('sys@CONF_DIR');
            // 总目录
            is_dirName($configDir);
            foreach ($env as $k => $v) {
                // 不是数字类型执行
                if (!is_numeric($k)) {
                    // 创建配置目录
                    if (!is_dir($configDir . $k)) {
                        if ($k) {
                            is_dirName($configDir . $k);
                        }
                    }
                }else{
                    return true;
                }
                //end for
            }
            return true;
        }else{
            exit(lang('run_is_name').'isRunMachineName()->is_numeric');
        }
    }

    /**
     * 初始化判断目录文件
     */
    protected static function loadDir(){
        header('X-Powered-By:'.Config::get('sys@UPADD_VERSION'));
        if(!self::isRunMachineName()){
            msg(10004,lang('loadRunConfig'));
        }

        $_data_dir = Config::get('sys@data_dir');

        // 数据资源文件夹
        if (! is_dir ( $_data_dir )){
            is_dirName ( $_data_dir );
        }

        // 日记目录
        if( ! is_dir($_data_dir . 'log')){
            is_dirName ( $_data_dir . 'log' );
        }

        //创建编译文件夹
        if(! is_dir($_data_dir.'compiled')){
            is_dirName($_data_dir.'compiled');
        }

        //创建缓存文件夹
        if(! is_dir($_data_dir.'cache')){
            is_dirName($_data_dir.'cache');
        }


    }






}