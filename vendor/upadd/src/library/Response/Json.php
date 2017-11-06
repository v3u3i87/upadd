<?php
namespace Upadd\Bin\Response;

class Json extends Factory
{
    public $contentType = 'application/json';

    public function setContent($data=null)
    {
        return (json_encode($data,JSON_UNESCAPED_UNICODE));
    }


}