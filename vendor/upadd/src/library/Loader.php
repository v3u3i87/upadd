<?php
/**
 * +----------------------------------------------------------------------
 * | UPADD [ Can be better to Up add]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2011-2015 http://upadd.cn All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: Richard.z <v3u3i87@gmail.com>
 **/

namespace Upadd\Bin;

use Upadd\Bin\Config\Configuration as Config;
use Upadd\Bin\Tool\Log;
use Upadd\Bin\UpaddException;

class Loader
{

    public static function Run()
    {
        self::is_create_data_dir();
        spl_autoload_register(function ($className) {

            $_filePath = UPADD_HOST . '/' . str_replace('\\', '/', $className) . '.php';
            if (is_file($_filePath)) {
                require $_filePath;

            } else {
                throw new UpaddException($_filePath . '文件不存在..');
            }

        });
    }

    /**
     * 判断是否创建
     */
    private static function is_create_data_dir()
    {
        header('X-Powered-By:' . Config::get('sys@upadd_version'));
        $is_data = true;
        if ($is_data) {
            self::is_create_confg_dir();
            $_data_dir = Config::get('sys@data_dir');
            // 数据资源文件夹
            if (!is_dir($_data_dir)) {
                is_create_dir($_data_dir);
            }

            // 数据资源文件夹
            if (!is_dir($_data_dir . APP_NAME)) {
                is_create_dir($_data_dir . APP_NAME);
            }

            // 日记目录
            if (!is_dir($_data_dir . APP_NAME . '/log')) {
                is_create_dir($_data_dir . APP_NAME . '/log');
            }

            //创建编译文件夹
            if (!is_dir($_data_dir . APP_NAME . '/compiled')) {
                is_create_dir($_data_dir . APP_NAME . '/compiled');
            }

            //创建缓存文件夹
            if (!is_dir($_data_dir . APP_NAME . '/cache')) {
                is_create_dir($_data_dir . APP_NAME . '/cache');
            }

            //上传文件目录
            if (!is_dir($_data_dir . 'upload')) {
                is_create_dir($_data_dir . 'upload');
            }
        }
    }


    /**
     * 创建配置文件目录
     * @return bool
     */
    private static function is_create_confg_dir()
    {
        if ($env = Config::get('sys@environment')) {
            $configDir = Config::get('sys@config_dir');
            // 总目录
            is_create_dir($configDir);
            foreach ($env as $k => $v) {
                // 不是数字类型执行
                if (!is_numeric($k)) {
                    // 创建配置目录
                    if (!is_dir($configDir . $k)) {
                        if ($k) {
                            is_create_dir($configDir . $k);
                        }
                    }
                } else {
                    return true;
                }
                //end for
            }
            return true;
        }
    }


}