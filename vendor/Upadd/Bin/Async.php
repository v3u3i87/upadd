<?php
namespace Upadd\Bin;

use Upadd\Bin\Async\Http;

class Async
{

    public static function __callStatic($method, $parameters)
    {
        if($method == 'http' || $method == 'Http' )
        {
            return (new Http());
        }
    }



}