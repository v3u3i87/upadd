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
use Upadd\Bin\Tool\Log;
/**
 * 自定义异常
 * Class UpaddException
 * @package Upadd\Bin
 */
class UpaddException extends Exception
{

    public function __construct($msg = '', $code = 10000,Exception $previous = null)
    {
        $this->writeLog($msg,$msg);

        parent::__construct($msg, $code, $previous);
    }

    private function writeLog($msg,$code = 10000)
    {
        $body = "UpaddException\n";
        $body .= "--\n";
        $body .= "Msg:" . $msg . "\n";
        $body .= "Code:" . $code . "\n";
        $body .= "--\n";
        Log::run($body);
    }

}