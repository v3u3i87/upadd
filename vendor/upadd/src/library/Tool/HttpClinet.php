<?php

namespace extend\tool;

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 2018/1/12
 * Time: 下午3:57
 * Name:
 */
use Log;

class HttpClinet
{

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


    public function offLog($fileNmae)
    {
        $this->is_log = true;
        $this->logFileName = $fileNmae;
    }


    /**
     * @return static
     */
    public static function init()
    {
        return new static();
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
        $this->send();
        return $this->responseData;
    }

    private function isLog($body)
    {
        if ($this->is_log) {
            Log::notes($body, $this->logFileName);
        }
    }


    /**
     * CRUL方法
     * @param array $_param
     * @return array|bool
     */
    private function send()
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            if ($this->header) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->methods);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
            //数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $body = curl_exec($ch);
            curl_close($ch);
            $this->isLog($body);
            $this->isResponseType($body);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }


    /**
     * @param $body
     * @return array|mixed
     */
    private function isResponseType($body)
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
    private function xmlToArray($xml)
    {
        $this->responseData = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $this->responseData;
    }

    /**
     * @param $json
     * @return array|mixed
     */
    private function jsonToArray($json)
    {
        $this->responseData = json_decode($json, true);
        return $this->responseData;
    }


}