<?php

namespace Upadd\Swoole;

use Config;
use Upadd\Bin\UpaddException;
use Upadd\Swoole\Lib\Help;

class Client
{
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
     * @var
     */
    protected $client;

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
     * 监听状态
     * @var bool
     */
    protected $is_monitor = false;


    /**
     *
     */
    public function monitor()
    {
        $this->is_monitor = true;
    }

    protected $is_ssl = false;


    /**
     * 开启SSL通信
     */
    public function offSSL()
    {
        $this->is_ssl = true;
    }

    protected $sslPath = [];

    /**
     * 设置SSL文件路径
     * @param $ssl_cert_file_path
     * @param $ssl_key_file_path
     */
    public function setSslPath($ssl_cert_file_path, $ssl_key_file_path)
    {
        $this->sslPath = [
            'ssl_cert_file' => $ssl_cert_file_path,
            'ssl_key_file' => $ssl_key_file_path,
        ];
    }


    /**
     * Client constructor.
     * @param $address
     * @param null $data
     */
    public function __construct($address = null, $data = null)
    {
        if ($address) {
            $this->parsing($address);
        }
        if ($data) {
            $this->data = $data;
        }
    }

    /**
     * @param $address
     * @param null $data
     * @return static
     */
    public static function create($address = null, $data = null)
    {
        return new static($address, $data);
    }


    /**
     * @param $address
     */
    private function parsing($address)
    {
        $parse = Help::parseAddress($address);
        if (isset($parse['scheme'])) {
            $this->type = $parse['scheme'];
        }

        if (isset($parse['host'])) {
            $this->host = $parse['host'];
        }

        if (isset($parse['port'])) {
            $this->port = $parse['port'];
        }
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
     * 请求方式设置
     * @return string
     */
    public function setMethods($type = 'POST')
    {
        return $this->methods = $type;
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

    /**
     * @param $data
     */
    public function setSendData($data)
    {
        $this->data = $data;
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


    /**
     * @param $body
     */
    protected function isLog($body)
    {
        if ($this->is_log) {
            Log::notes($body, $this->logFileName);
        }
    }


}