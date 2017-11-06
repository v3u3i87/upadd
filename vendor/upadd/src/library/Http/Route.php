<?php
namespace Upadd\Bin\Http;

use Upadd\Bin\UpaddException;

class Route
{

    public $prefix = '';

    public $filters = '';

    public $_resou = array();

    public static $action = null;

    public static $method = null;

    public $_tmp = array();

    public $request = null;


    /**
     * @param $request
     */
    public function getRequest($Request)
    {
        $this->request = $Request;
    }

    /**
     * URL组
     * @param array $method
     * @param       $_callback
     */
    public function group($method = array(), $_callback = null)
    {
        if (array_key_exists('prefix', $method)) {
            $this->prefix = $method['prefix'];
        }

        if (array_key_exists('filters', $method)) {
            $this->filters = $method['filters'];
        }

        if (is_callable($_callback)) {
            return call_user_func($_callback);
        }
    }

    /**
     * 过滤器
     * @param $key
     * @param $_callback
     */
    public function filters($key, $_callback)
    {
        $_urlKey = $this->request->setUrlHash();
        if (isset($this->_resou[$_urlKey])) {
            //获取本次请求路由资源
            $resou = $this->_resou[$_urlKey];
            if ($resou['filters'] == $key && is_callable($_callback)) {
                return call_user_func($_callback);
            }
        }
    }


    /**
     * 判断方法模式
     * @param $method
     * @return callable|null|string
     */
    private function is_method($method, $callable = null)
    {
        if (is_callable($method)) {
            $callable = $method;
        } elseif (is_string($method)) {
            $callable = $method;
        }
        return $callable;
    }


    /**
     * 缓存请求
     * @param $url
     * @param $method
     * @param $type
     */
    private function setCacheRequest($url = '', $method = null, $type = '')
    {
        $method = $this->is_method($method);
        $url = $this->setUrl($url);
        $this->_resou[$url] = array(
            'methods' => $method,
            'callbacks' => $method,
            'url' => $url,
            'type' => $type,
            'prefix' => $this->prefix,
            'filters' => $this->filters
        );

    }

    /**
     * 设置URL
     * @param $url
     * @return string
     */
    private function setUrl($url)
    {
        if ($this->request->getPathUrl() === $url) {
            return $url;
        } else {
            $url = $this->prefix . $url;
        }
        return $url;
    }

    /**
     * 获取资源
     * @return bool || array
     */
    public function resources()
    {
        $currentRequest = $this->request->getPathUrl();
        if ($this->_resou)
        {
            if (isset($this->_resou[$currentRequest])) {
                return $this->isRequestMethod($this->_resou[$currentRequest]);
            } else {
                foreach ($this->_resou as $name => $item) {
                    $key = $this->setRewrite($name, $currentRequest);
                    if ($key) {
                        return $this->isRequestMethod($this->_resou[$key]);
                    }
                }
            }
        } else {
            throw new UpaddException('Please set routing');
        }
    }


    /**
     * 判断请求模式
     * @param $request
     * @return mixed
     * @throws UpaddException
     */
    private function isRequestMethod($request)
    {
        if ($this->request->getRequestMethod() == $request['type'] || $request['type'] == 'ANY') {
            return $request;
        } else {
            throw new UpaddException("Request the wrong way, your source is ({$this->request->getRequestMethod()}), the request is {$request['type']}.");
        }
    }

    /**
     * 获取控制器
     * @param null $_action
     * @param null $_method
     */
    public function setAction($_action = null, $_method = null)
    {
        if ($_action && $_method) {
            static::$action = $_action;
            static::$method = $_method;
        }
    }

    /**
     * @param $name
     * @param $parameters
     */
    public function __call($name, $parameters)
    {
        $url = $parameters[0];
        $action = $parameters[1];
        $method = strtoupper($name);
        /**
         * $url 访问地址
         * $action 访问控制器
         * $method 访问类型
         */
        $this->setCacheRequest($url, $action, $method);
    }


}