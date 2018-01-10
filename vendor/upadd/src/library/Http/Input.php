<?php

namespace Upadd\Bin\Http;

abstract class Input
{

    /**
     * 数据
     * @var array
     */
    protected $data = array();

    /**
     * 数据流
     * @var null
     */
    protected $stream = null;

    /**
     * json对象
     * @var null
     */
    protected $json = null;


    /**
     * Data constructor.
     */
    public function __construct()
    {
        $this->setGet();
        $this->setPost();
        $this->setFiles();
        $this->setStream();
        $this->setJson();
    }


    /**
     * 设置json
     */
    private function setJson()
    {
        $json = $this->stream;
        if ($json) {
            $json = json_decode($json, true);
            if (count($json) >= 1) {
                $this->json = $json;
            }
        }
    }


    /**
     * 设置POST参数
     */
    private function setPost()
    {
        if (count($_POST) >= 1) {
            $this->data = array_merge($this->data, $_POST);
        }
    }

    /**
     * 设置GET参数
     */
    private function setGet()
    {
        if (count($_GET) >= 1) {
            $this->data = array_merge($this->data, $_GET);
        }
    }

    /**
     * 设置文件
     */
    private function setFiles()
    {
        if (count($_FILES) >= 1) {
            $this->data = array_merge($this->data, $_FILES);
        }
    }

    /**
     * 获取数据流
     * @return array|string
     */
    public function setStream($data = null)
    {
        if (empty($data)) {
            $data = !empty(file_get_contents("php://input")) ? file_get_contents("php://input") : null;
        }
        $this->stream = $data;
    }


    /**
     * 接受数据
     * @param array $data
     */
    public function accept($data = [])
    {
        if (count($data) >= 1) {
            return ($this->data = array_merge($this->data, $data));
        }
    }

    abstract public function get($name = null, $default = null, $method = null);

    abstract public function stream();

    abstract public function json();

    abstract public function all();

}