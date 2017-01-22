<?php
/**
 * +----------------------------------------------------------------------
 * | UPADD [ Can be better to Up add]
 * +----------------------------------------------------------------------
 * | Copyright (c) 2011-2015 http://upadd.cn All rights reserved.
 * +----------------------------------------------------------------------
 * | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * +----------------------------------------------------------------------
 * | Author: Richard.z <v3u3i87@gmail.com>
 **/
namespace Upadd\Bin\Http;

use Config;
use Data;
use Upadd\Bin\Tool\Log;
use Upadd\Bin\UpaddException;


class Request
{

    /**
     * 请求容器
     * @var array
     */
    public $_routing = array();

    public $_reqUrl = '/';

    public $server = [];

    public $header = [];


    /**
     * 请求日志
     * @pamer
     */
    public function setRequestLog()
    {
        $Requset = '';
        if (is_run_evn()) {
            if (isset($this->server['request_uri'])) {
                $Requset = $this->server['request_uri'];
            }

        } else {
            $Requset = 'cli';
        }
        $body = 'Run Start' . "\n";
        $body .= 'Host:' . (isset($this->server['http_host']) ? $this->server['http_host'] : '') . "\n";
        $body .= 'Requset:' . $Requset . "\n";
        $body .= 'Ip:' . getClient_id() . "\n";
        $body .= 'Method:' . (isset($this->server["request_method"]) ? $this->server["request_method"] : 'cli') . "\n";
        $body .= 'Header:' . json($this->header) . "\n";
        $body .= 'Parameter:' . json(Data::all()) . "\n";
        Log::run($body);
    }


    /**
     * 获取当前的URL
     * @return mixed
     */
    public function getPathUrl()
    {
        return parse_url((isset($this->server['request_uri']) ? $this->server['request_uri'] : '/'), PHP_URL_PATH);
    }

    /**
     * 获取请求类型
     * @return mixed
     */
    public function getRequestMethod()
    {
        return strtoupper($this->server['request_method']);
    }

    /**
     * 设置URL哈希加密
     * @return string
     */
    public function setUrlHash()
    {
        return $this->getPathUrl();
    }

    /**
     * 处理路由请求参数
     * @param $setResou
     * @return string
     */
    public function setRewrite($setResou, $currentRequest)
    {

        if ($setResou == '/') {
            return false;
        }

        $regex = '@' . $setResou . '((?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
        if (preg_match($regex, $currentRequest, $m)) {
            $arr = explode('/', $m[1]);
            $arr = array_associated_index($arr);
            $arr && Data::accept($arr);
            return $setResou;
        }
        return false;

    }

    /**
     * 设置请求参数
     * @param      $spec
     * @param null $value
     * @return $this
     */
    private function setGetParam($val)
    {
        $val = substr($val, 0, -1);
        $val = lode(',', $val);
        foreach ($val as $k => $v) {
            $tmpLode = lode(':', $v);
            if (count($tmpLode) == 2) {
                list($_key, $value) = $tmpLode;
                Data::accept([$_key => $value]);
            }
        }
    }

}