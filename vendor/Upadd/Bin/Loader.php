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

    public static function Run()
    {
        header('X-Powered-By:'.UPADD_VERSION);
        spl_autoload_register (function($className){

            switch ($className) {
                case file_exists(ACTION_PAHT . $className . IS_EXP) :
                        $className = ACTION_PAHT . $className .IS_EXP;
                        require ($className);
                    break;

                case  file_exists(CHECK_PAHT . $className . IS_EXP):
                        $className = CHECK_PAHT . $className .IS_EXP;
                        require ($className);
                    break;

                case  file_exists(LOGIC_PAHT . $className . IS_EXP):
                        $className = LOGIC_PAHT .$className .IS_EXP;
                        require ($className);
                    break;

                case  file_exists(MODEL_PAHT . $className . IS_EXP):
                        $className = lcfirst(MODEL_PAHT . $className .IS_EXP);
                        require ($className);
                    break;

                default:
                        $className = self::loadNameFile($className);
                        if(file_exists($className)){
                            require ($className);
                        }else{
                            is_exit($className."加载错误");
                        }
                    break;
            }

        });
        self::runTime();
    }


    protected static function loadNameFile($file=null){
        if($file){
            $file = str_replace("\\", "/", $file).IS_EXP;
            $file = UPADD_HOST.lcfirst($file);
            return  $file;
        }else{
            return false;
        }
    }

    protected static function runTime()
    {
        if(IS_RUNTIME){
            $endtime=microtime(true);
            $total=$endtime-RUNTIME;
            Log::write($total,'runTime.log');
        }
    }






}