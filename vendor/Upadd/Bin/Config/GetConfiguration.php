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
    public function get($key=''){
        try{
            if(list($_key,$val) = lode('@',$key)){
                if(isset(static::$_config[$_key][$val])) {
                    return static::$_config[$_key][$val];
                }
            }
            return false;
        }catch (\Exception $e){
            p($e->getMessage());
        }
    }

}
