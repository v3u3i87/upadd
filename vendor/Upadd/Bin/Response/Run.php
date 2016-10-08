<?php
namespace Upadd\Bin\Response;

use Upadd\Bin\Response\Json as ResponseJson;
use Upadd\Bin\Response\Xml as ResponseXml;
use Upadd\Bin\Response\View;
use Upadd\Bin\UpaddException;

class Run
{

    /**
     * 默认类型 html
     * @var string
     */
    protected $contentTypeHtml = 'text/html';

    /**
     * json类型
     * @var string
     */
    protected $contentTypeJson = 'application/json';

    /**
     * xml类型
     * @var string
     */
    protected $contentTypeXml = 'text/xml';

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
     * 构建数据
     * Run constructor.
     */
    public function __construct($data, $type = null)
    {
        $this->content = $data;
        $this->type = $type;
    }

    /**
     * 判断类型
     * @return string
     */
    protected function is_type()
    {
        return gettype($this->content);
    }

    /**
     * 设置xml
     * @return mixed
     */
    public function setXml()
    {
        $xml = new ResponseXml();
        return $xml->execute($this->content);
    }


    /**
     * 设置json
     * @return string
     */
    public function setJson()
    {
        return json_encode($this->content, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 转换
     */
    public function change()
    {
        switch ($this->is_type()) {
            case 'array':
                if ($this->type == 'json') {
                    $this->content = $this->setJson();
                    $this->contentType($this->contentTypeJson);
                } elseif ($this->type == 'xml') {
                    $this->content = $this->setXml();
                    $this->contentType($this->contentTypeXml);
                } else {
                    throw new UpaddException('Response The return type only supports json or xml');
                }
                break;

            case 'string':
                $this->contentType($this->contentTypeHtml);
                break;
        }
    }

    /**
     * 页面输出类型
     * @param string $contentType 输出类型
     * @param string $charset 输出编码
     * @return $this
     */
    public function contentType($contentType, $charset = 'utf-8')
    {
        $this->header['Content-Type'] = $contentType . '; charset=' . $charset;
    }

    /**
     * 发送客户端
     */
    public function send()
    {
        $this->change();
        if (headers_sent() == false) {
            // 发送状态码
            http_response_code($this->code);
            // 发送头部信息
            foreach ($this->header as $name => $val) {
                header($name . ':' . $val);
            }
        }
        echo $this->content;
//        function_exists('fastcgi_finish_request') && fastcgi_finish_request();
    }


    /**
     * 临时调试代码
     * @param $name
     * @param $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        /**
         * 暂时过度
         */
        if ($name === 'debug') {
            echo json($arguments);
        }
    }

    /**
     * 设置协议状态码
     * @param $code
     * @auth sys
     * @time 2016-7-20
     */
    public function set_response_code($code)
    {
        $this->code = $code;
    }


}