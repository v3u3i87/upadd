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

use Upadd\Bin\Log;

class Loader
{
    /**
     * 自动加载对外拓展对象
     * @var array
     */
    private static $_autoload = array();

    public static function Run()
    {
        self::loadDir();
        self::sessionStart();
        spl_autoload_register (function($className)
        {
            //判断是否开启自定义
            if(conf('start@is_autoload')){
                $autoload  = self::getAutoload();
                $className = lode("\\",$className);
                $className =  end($className);
                foreach($autoload as $k=>$v){
                    $_filePath = UPADD_HOST.$v.$className.IS_EXP;
                    if(is_file($_filePath)){
                        break;
                    }
                }
            }else{
                $_filePath =  UPADD_HOST . str_replace('\\', '/', $className) . IS_EXP;
            }

            if(is_file($_filePath)){
                require $_filePath;
            }
        });

        self::runRequest();
    }


    /**
     * 获取命名空间规则
     * @return string
     */
    public static function getAutoload()
    {
        $autoload = conf('start@autoload');
        //判断是否启用插件
        if(APP_PLUG){
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
     * 判断是否开启 session
     */
    private static function sessionStart(){
        $state = conf('conf@session_start');
        if($state) {
            session_start();
        }
    }


    /**
     * 记录运行时间
     * @pamer
     */
    private static function runRequest()
    {
        if(IS_RUNTIME){
            $endtime = (microtime(true)) - RUNTIME;
            $_header = getHeader();
            Log::request(array(
                'method'=>$_SERVER["REQUEST_METHOD"],
                'header'=>$_header,
                'param'=>$_REQUEST,
                'run_time'=>$endtime,
            ));
        }
    }


    /**
     * 判断运行环境
     * @return bool
     */
    private static function isRunMachineName(){
        $env = conf('start@environment');
        //merge in config array
        $oneEnv = array_merge_one($env);
        $osName = getMachineName();
        if(in_array($osName,$oneEnv)){
            // 总目录
            is_dirName(CONF_DIR);
            foreach ($env as $k => $v) {
                // 不是数字类型执行
                if (!is_numeric($k)) {
                    // 创建配置目录
                    if (!is_dir(CONF_DIR . $k)) {
                        if ($k) {
                            is_dirName(CONF_DIR . $k);
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



    protected static function loadDir(){
        header('X-Powered-By:'.UPADD_VERSION);
        if(!self::isRunMachineName()){
            msg(10004,lang('loadRunConfig'));
        }

        // 数据资源文件夹
        if (! is_dir ( DATA_DIR )){
            is_dirName ( DATA_DIR );
        }

        // 日记目录
        if( ! is_dir(DATA_DIR . 'log')){
            is_dirName ( DATA_DIR . 'log' );
        }

        //创建编译文件夹
        if(! is_dir(DATA_DIR.'compiled')){
            is_dirName(DATA_DIR.'compiled');
        }

        //创建缓存文件夹
        if(! is_dir(DATA_DIR.'cache')){
            is_dirName(DATA_DIR.'cache');
        }

    }






}