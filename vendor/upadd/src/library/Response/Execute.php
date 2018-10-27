<?php
namespace Upadd\Bin\Response;

use Upadd\Bin\Response\Json;
use Upadd\Bin\Response\Xml;
use Upadd\Bin\Response\View;
use Upadd\Bin\UpaddException;

class Execute
{

    public $type = null;

    public $content = null;

    public $header = [];

    public $code = 200;

    private function getJson()
    {
        return new Json();
    }

    private function getXml()
    {
        return new Xml();
    }

    private function getView()
    {
        return new View();
    }

    /**
     * 判断类型
     * @return string
     */
    private function isType()
    {
        return gettype($this->content);
    }

    /**
     * 输出类型
     * @param string $contentType 输出类型
     * @param string $charset 输出编码
     * @return $this
     */
    private function contentType($contentType, $charset = 'utf-8')
    {
        $this->header['Content-Type'] = $contentType . '; charset=' . $charset;
    }


    /**
     * @return mixed
     */
    protected function getObj()
    {
        $obj = [
            'json' => $this->getJson(),
            'xml' => $this->getXml(),
        ];
        $type = $this->isType();
        if ($type == 'array') {
            if (isset($obj[$this->type])) {
                return $obj[$this->type];
            } else {
                throw new UpaddException('Response The return type only supports json or xml');
            }
        }
        return $this->getView();
    }

    /**
     * 发送客户端
     */
    public function sendHttp()
    {
        $obj = $this->getObj();

        $this->contentType($obj->contentType);
        $this->content = $obj->setContent($this->content);
        if (headers_sent() == false)
        {
            // 发送状态码
            http_response_code($this->code);
            // 发送头部信息
            foreach ($this->header as $name => $val)
            {
                header($name . ':' . $val);
            }
        }
        return $this->content;
    }


    /**
     * 发送客户端
     */
    public function command()
    {
        $obj = $this->getObj();
        $this->content = $obj->setContent($this->content);
        echo $this->content;
    }

    public function sendSwooleHtpp()
    {
        $obj = $this->getObj();
        $this->contentType($obj->contentType);
        $this->content = $obj->setContent($this->content);
        return ['code'=>$this->code,'header'=>$this->header,'data'=>$this->content];
    }

}