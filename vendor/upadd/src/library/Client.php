<?php

namespace Upadd\Bin;

use Upadd\Swoole\Lib\Help;
use Upadd\Bin\Client\CurlOperation;

abstract class Client
{
    use CurlOperation;

    /**
     * @var null
     */
    protected $host = null;

    /**
     * @var null
     */
    protected $type = null;

    /**
     * set in port
     * @var null
     */
    protected $port = null;

    /**
     * 提交url
     * @var string
     */
    public $url = '';

    /**
     * 提交数据
     * @var array
     */
    public $data = [];

    /**
     * 头设置
     * @var array
     */
    public $header = [];

    /**
     * 默认POST
     * @var string
     */
    public $methods = 'POST';

    /**
     * 超时设置
     * @var int
     */
    public $timeOut = 60;

    /**
     * 响应数据
     * @var array
     */
    private $responseData = [];

    /**
     * 日志文件名称
     * @var string
     */
    private $logFileName = '';

    /**
     * 判断是否开启日志
     * @var bool
     */
    private $is_log = false;

    /**
     * 响应类型,默认json
     * @var string
     */
    public $responseType = 'json';


    /**
     * 操作
     * @var array
     */
    protected $operation = [];


    /**
     * Client constructor.
     * @param $address
     * @param null $data
     */
    public function __construct($address, $data = null)
    {
        $parse = Help::parseAddress($address);
    }

    /**
     * @param $address
     * @param null $data
     * @return static
     */
    public static function create($address, $data = null)
    {
        return new static($address, $data);
    }


    public function offLog($fileNmae)
    {
        $this->is_log = true;
        $this->logFileName = $fileNmae;
    }


    /**
     * 设置响应格式为XML
     */
    public function setResponseXml()
    {
        $this->responseType = 'xml';
    }

    /**
     * 设置为GET
     * @return string
     */
    public function setMethodsGet()
    {
        return $this->methods = 'GET';
    }

    /**
     * 设置为POST
     * @return string
     */
    public function setMethodsPost()
    {
        return $this->methods = 'POST';
    }


    public function __set($name, $value)
    {
        $this->setData($name, $value);
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return '';
        }
    }

    /**
     * @param $key
     * @param $value
     * @return array
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getResponse()
    {
        return $this->responseData;
    }

    /**
     * @param $body
     * @return array|mixed
     */
    protected function isResponseType($body)
    {
        if ($this->responseType == 'json' || $this->responseType === 'json') {
            return $this->jsonToArray($body);
        }

        if ($this->responseType == 'xml' || $this->responseType === 'xml') {
            return $this->xmlToArray($body);
        }
    }

    /**
     * 将XML转为array
     * @param $xml
     * @return mixed
     */
    protected function xmlToArray($xml)
    {
        $this->responseData = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->responseData;
    }

    /**
     * @param $json
     * @return array|mixed
     */
    protected function jsonToArray($json)
    {
        $this->responseData = json_decode($json, true);
        return $this->responseData;
    }


    protected function isLog($body)
    {
        if ($this->is_log) {
            Log::notes($body, $this->logFileName);
        }
    }


    /**
     * @return mixed
     */
    abstract public function sync();

    /**
     * @return mixed
     */
    abstract public function async();

    /**
     * @return mixed
     */
    abstract public function close();


}