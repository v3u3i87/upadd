<?php

namespace Upadd\Bin\Http;

class Data{

    public $_setData = array();

    /**
     * Data constructor.
     */
    public function __construct()
    {
        $this->setGet();
        $this->setPost();
        $this->setFiles();
    }

    /**
     * 设置POST参数
     */
    private function setPost()
    {
        if(count($_POST) >= 1)
        {
            $this->_setData = array_merge($this->_setData,$_POST);
        }
    }

    /**
     * 设置GET参数
     */
    private function setGet()
    {
        if(count($_GET) >= 1)
        {
            $this->_setData = array_merge($this->_setData,$_GET);
        }
    }

    /**
     * 设置文件
     */
    private function setFiles()
    {
        if(count($_FILES) >= 1)
        {
            $this->_setData = array_merge($this->_setData,$_FILES);
        }
    }

    /**
     * 对外获取的方法
     * @param null $name
     * @param null $default
     * @param null $method
     * @return mixed|null
     */
    public function get($name=null,$default=null,$method=null)
    {

        if(isset($this->_setData[$name]))
        {
            $default = $this->_setData[$name];
        }

        if(is_callable($method))
        {
            return call_user_func($method,$default);
        }

        return $default;
    }

    /**
     * 返回所有的请求数据
     * @return array
     */
    public function all()
    {
       return $this->_setData;
    }

    /**
     * 接受数据
     * @param array $data
     */
    public function accept($data=[])
    {
        if(count($data) >= 1)
        {
           return ($this->_setData = array_merge($this->_setData,$data));
        }
    }

}