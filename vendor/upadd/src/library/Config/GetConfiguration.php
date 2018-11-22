<?php
/**
 * +----------------------------------------------------------------------
 * | UPADD [ Can be better to Up add]
 * +----------------------------------------------------------------------
 * | Copyright (c) 20011-2017 http://github.com/v3u3i87/upadd All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: Richard.z <v3u3i87@gmail.com>
 **/

namespace Upadd\Bin\Config;

use Upadd\Bin\Di;

class GetConfiguration
{

    public $configMap = [];

    public function __construct()
    {
        $this->configMap = Di::getConfig();
    }


    /**
     * 获取数据
     * @param $key
     */
    public function get($key = '')
    {
        if (list($file, $name) = lode('@', $key)) {
            if (isset($this->configMap[$file][$name])) {
                return $this->configMap[$file][$name];
            }
            $Configuration = Di::get('Configuration');
            $config = $Configuration->getConfLoad($file);
            if (is_array($config)) {
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
    public function setFileVal($name, $key, $val)
    {
        if (isset($this->configMap[$name][$key])) {
            return $this->configMap[$name][$key] = $val;
        } else {
            $tmp[$key] = $val;
            $now_name = $this->configMap[$name];
            $tmp = array_merge($now_name, $tmp);
            return $this->configMap[$name] = $tmp;
        }
        return false;
    }

    public function all()
    {
        return $this->configMap;
    }

}
