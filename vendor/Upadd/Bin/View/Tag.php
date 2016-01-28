<?php
namespace Upadd\Bin\View;
/**
+----------------------------------------------------------------------
| UPADD [ Can be better to Up add]
+----------------------------------------------------------------------
| Copyright (c) 2011-2015 http://upadd.cn All rights reserved.
+----------------------------------------------------------------------
| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
+----------------------------------------------------------------------
| Author: Richard.z <v3u3i87@gmail.com>
 **/

use Upadd\Bin\UpaddException;
use Config;

class Tag{

    public $_file = '';

    public $_KeyArr = array();

    public $front_domain = '';

    /**
     * Tag constructor.
     * @param $file
     * @param $_KeyArr
     */
    public function __construct($file,$_KeyArr)
    {
        /**
         * 模板文件字符串
         */
        $this->_file = $file;
        /**
         * 获取模板变量数组
         */
        $this->_KeyArr = $_KeyArr;

        /**
         * 判断是否加载前端域名
         */
        if(Config::get('tag@is_front_domain'))
        {
            $this->front_domain = Config::get('tag@front_domain');
        }

    }


    /**
     * 批量替换
     */
    public function pregVal() {
        $preaa = array (
            '/<\!--\s\$([\w]+)\s\-->/',
            '/<\!--\s+if\s+\$([\w]+)\s+\-->/',
            '/<\!--\s+\/if\s+\-->/',
            '/<\!--\s+else\s+\-->/',
            '/\@loop\$([\w]+)\(([\w]+),([\w]+)\)/',
            '/<\!--\s+\@([\w]+\[\'[\w]+\'\])\s+\-->/',
            '/<\!--\s+\/loop\s+\-->/',
            '/<\!--\s+\#(.*)\s+\-->/',
            '/<\!--\s+row\s+\$([\w]+)\(([\w]+),([\w]+)\)\s+\-->/',
            '/<\!--\s+\/row\s+\-->/',
            '/<\!--\s+@([\w]+)\s+\-->/',
            '/<\!--\s+\#\#(.*)\s+\-->/',
            '/<\!--\s+obj\s+\$([\w]+)\(([\w]+),([\w]+)\)\s+\-->/',
            '/<\!--\s+\/obj\s+\-->/',
            '/<\!--\s+@([\w]+)([\w\-\>\+]*)\s+\-->/',
            '/<\!--\${(.*)\}\-->/'
        );
        $prebb = array (
            '<?php echo \$this->_KeyArr["$1"];?>',
            '<?php if (\$this->_KeyArr["$1"]) {?>',
            '<?php } ?>',
            '<?php } else { ?>',
            '<?php foreach (\$this->_KeyArr["$1"] as \$$2=>\$$3) { ?>',
            '<?php echo \$$1; ?>',
            '<?php } ?>',
            '<?php /* $1 */ ?>',
            '<?php foreach (\$this->_KeyArr["$1"] as \$$2=>\$$3) { ?>',
            '<?php } ?>',
            '<?php echo \$$1; ?>',
            '<?php /* $1 */ ?>',
            '<?php foreach (\$this->_KeyArr["$1"] as \$$2=>\$$3) { ?>',
            '<?php } ?>',
            '<?php echo \$$1$2; ?>'
        );
        $this->_file = preg_replace ( $preaa, $prebb, $this->_file );
        if (preg_match ( $preaa [0], $this->_file )) {
            $this->_file = $this->setArr ( $this->_file );
        }
        return $this->_file;
    }

    /**
     * 加载前端资源文件
     * @return mixed|string
     */
    public function style(){
        return ($this->_file = preg_replace ( array(
            "/\@load\(\'(.*?)\'\)/i",
            "/\@css\(\'(.*?)\'\)/i",
            "/\@public_css\(\'(.*?)\'\)/i",
            "/\@js\(\'(.*?)\'\)/i",
            "/\@public_js\(\'(.*?)\'\)/i",
        ), array(
            '<?php include host()."works/view/$1"; ?>',
            $this->css(),
            $this->public_css(),
            $this->js(),
            $this->public_js()
        ), $this->_file ));
    }

    /**
     * 指定目录的CSS
     * @return string
     */
    public function css(){
        $css = $this->front_domain.'/resou/css/'."$1";
        return "<link rel=\"stylesheet\" href=\"{$css}\">";
    }

    /**
     * 公共目录的CSS
     * @return string
     */
    public function public_css()
    {
        $css = $this->front_domain."$1";
        return "<link rel=\"stylesheet\" href=\"{$css}\">";
    }

    /**
     * 获取指定目录的JS
     * @return string
     */
    public function js(){
        $js = $this->front_domain.'/resou/js/'."$1";
        return "<script type=\"text/javascript\" src=\"{$js}\"></script>";
    }


    /**
     * 获取公共目录的JS
     * @return string
     */
    public function public_js(){
        $js = $this->front_domain."$1";
        return "<script type=\"text/javascript\" src=\"{$js}\"></script>";
    }


    public function val(){
        return ($this->_file = preg_replace (array(
            "/\@val\(\'(.*?)\'\)/i",
        ), array(
            '<?php echo \$$1;?>',
        ), $this->_file));
    }

    /**
     * 对外访问的编译文件
     * @return string
     */
    public function Compile(){
        $this->style();
        $this->val();
        return $this->_file;
    }

}