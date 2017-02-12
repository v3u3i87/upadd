<?php
namespace Upadd\Bin\Response;


abstract class Factory
{

    /**
     * 内容类型
     * @var string
     */
    protected $contentType = null;

    /**
     * 字符集
     * @var string
     */
    protected $charset = 'utf-8';

    /**
     * 默认
     * @var int
     */
    protected $code = 200;

    /**
     * 输出参数
     * @var array
     */
    protected $options = [];

    /**
     * header参数
     * @var array
     */
    protected $header = [];

    /**
     * 输出内容
     * @var null
     */
    protected $content;

    /**
     * 响应类型
     * @var null
     */
    protected $type;


    /**
     * 设置内容
     * @param null $data
     * @return mixed
     */
    abstract public function setContent($data=null);



}