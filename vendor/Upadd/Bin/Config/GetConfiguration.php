<?php
/**
+----------------------------------------------------------------------
| UPADD [ Can be better to Up add]
+----------------------------------------------------------------------
| Copyright (c) 20011-2015 http://upadd.cn All rights reserved.
+----------------------------------------------------------------------
| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
+----------------------------------------------------------------------
| Author: Richard.z <v3u3i87@gmail.com>
 **/
namespace Upadd\Bin\Config;

use Upadd\Bin\Application;

class GetConfiguration extends Application{

    /**
     * 获取数据
     * @param $key
     */
    public function get($key='')
    {
        if(list($file,$name) = lode('@',$key))
        {
            if(isset(static::$_config[$file][$name]))
            {
                return static::$_config[$file][$name];
            }
            $config = $this->getConfiguration()->getConfLoad($file);
            if(is_array($config))
            {
                return $config[$name];
            }
        }
        return false;
    }

    /**
     * 设置系统全局函数
     * @param $name 文件名
     * @param $key  下标
     * @param $val  数据
     * @return array|bool
     */
    public function setFileVal($name,$key,$val)
    {
        if(isset(static::$_config[$name][$key]))
        {
            return static::$_config[$name][$key] = $val;
        }else{
            $tmp[$key] = $val;
            $now_name = static::$_config[$name];
            $tmp = array_merge($now_name,$tmp);
            return static::$_config[$name] =$tmp;
        }
        return false;
    }

    public function all()
    {
        return static::$_config;
    }

}
