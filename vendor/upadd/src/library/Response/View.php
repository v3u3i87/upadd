<?php

namespace Upadd\Bin\Response;


class View extends Factory
{
    /**
     * 默认类型 html
     * @var string
     */
    public $contentType = 'text/html';

    public function setContent($data = null)
    {
        return $data;
    }

}