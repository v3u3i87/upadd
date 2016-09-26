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
namespace Upadd\Bin;

use Exception;

class UpaddException extends Exception
{

    /**
     * 错误异常构造函数
     * @param integer $errno 错误级别
     * @param string  $errstr  错误详细信息
     * @param string  $errfile     出错文件路径
     * @param integer $errline     出错行号
     * @param array   $errcontext  错误上下文，会包含错误触发处作用域内所有变量的数组
     */
//    public function __construct($errno, $errstr, $errfile, $errline, $errcontext=[])
//    {
//        print_r([$errno, $errstr, $errfile, $errline]);
//        print_r($errcontext);
//    }

    public function __construct($msg = '', $code = 10000,Exception $previous = null)
    {
        parent::__construct($msg, $code, $previous);
    }

}