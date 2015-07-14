<?php
/**
+----------------------------------------------------------------------
| UPADD [ Can be better to Up add]
+----------------------------------------------------------------------
| Copyright (c) 20011-2014 http://upadd.cn All rights reserved.
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
    public static $_autoload = array();

    public static function Run()
    {
        self::loadDir();
        header('X-Powered-By:'.UPADD_VERSION);
        self::sessionStart();
        spl_autoload_register (function($className)
        {
            $autoload = self::getAutoload();
            $className = lode("\\",$className);
            $className =  end($className);
            $_fileData = array();
            foreach($autoload as $k=>$v){
                $_filePath = UPADD_HOST.$v.$className.IS_EXP;
                $_fileData[] = $_filePath;
            }
            self::loadFile($_fileData);
        });
        self::runTime();
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
    public static function setAutoload($_autoloadArray)
    {
        if($_autoloadArray){
            self::$_autoload = $_autoloadArray;
        }
    }

    /**
     * 判断是否开启 session
     */
    public static function sessionStart(){
        $state = conf('conf@session_start');
        if($state) {
            session_start();
        }
    }


    /**
     * 加载文件
     * @param array $_fileData
     */
    protected static function loadFile($_fileData=array()){
        foreach($_fileData as $k=>$v){
            if(file_exists($v))
            {
               return require ($v);
            }
        }
    }

    /**
     * 记录运行时间
     */
    protected static function runTime()
    {
        if(IS_RUNTIME){
            $endtime=microtime(true);
            $total=$endtime-RUNTIME;
            Log::write($total,'runTime.log');
        }
    }


    /**
     * 判断运行环境
     * @return bool
     */
    protected static function isRunMachineName(){
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
            is_exit(lang('run_is_name').'isRunMachineName()->is_numeric');
        }
    }



    protected static function loadDir(){

        if(!self::isRunMachineName()){
            is_exit(json(array('code'=>10004,'msg'=>lang('loadRunConfig'),'data'=>array())));
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