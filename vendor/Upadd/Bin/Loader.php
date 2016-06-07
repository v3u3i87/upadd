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
use Upadd\Bin\UpaddException;

class Loader
{
    /**
     * 自动加载对外拓展对象
     * @var array
     */
    private static $_autoload = array();


    public static function Run()
    {

        spl_autoload_register (function($className)
        {

            /**
             * 自定义命名空间 - 默认不开启
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
        if(Config::get('sys@is_plug'))
        {
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




}