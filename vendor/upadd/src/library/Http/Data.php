<?php

namespace Upadd\Bin\Http;

/**
 * 参数获取处理
 * Class Data
 * @package Upadd\Bin\Http
 */
class Data extends Input
{

    /**
     * 对外获取的方法
     * @param null $name
     * @param null $default
     * @param null $method
     * @return mixed|null
     */
    public function get($name = null, $default = null, $method = null)
    {

        if (isset($this->data[$name])) {
            $default = $this->data[$name];
        }

        if (is_callable($method)) {
            return call_user_func($method, $default);
        }

        return $default;
    }

    /**
     * 获取数据流
     * @return array
     */
    public function stream()
    {
        if ($this->stream) {
            return $this->stream;
        }
        return null;
    }

    /**
     * 返回PHP数组
     * @return array|null
     */
    public function json()
    {
        if (is_array($this->json)) {
            return $this->json;
        }
        return null;
    }

    /**
     * 返回所有数据
     * @return array
     */
    public function all()
    {
        if (is_array($this->json)) {
            $this->data = array_merge($this->data, $this->json);
        }

        if (!empty($this->stream)) {
            $this->data = array_merge($this->data, ['stream' => $this->stream]);
        }
        return $this->data;
    }


    public function del()
    {
        if ($this->data) {
            $this->data = [];
        }

        if ($this->stream) {
            $this->stream = null;
        }
    }

}