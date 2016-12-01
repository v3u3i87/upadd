<?php
namespace Upadd\Bin\Response;

use Upadd\Bin\Response\Run as ResponseRun;

class Json extends ResponseRun
{

    public function execute()
    {
        return (json_encode($this->content,JSON_UNESCAPED_UNICODE));
    }


}