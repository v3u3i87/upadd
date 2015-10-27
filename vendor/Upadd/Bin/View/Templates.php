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

/**
 * 模板类
 * @author Richard.z
 */
class Templates {
	
	/**
	 * 缓存
	 *
	 * @var unknown
	 */
	private $_cache;
	
	/**
	 * 编译
	 *
	 * @var unknown
	 */
	private $_compiled;
	
	/**
	 * 模板文件
	 *
	 * @var unknown
	 */
	private $_htmlFile;
	
	/**
	 * 引入文件变量
	 *
	 * @var unknown
	 */
	private $_fileVar;
	
	/**
	 *
	 * @var unknown
	 */
	private $_keyArr = array ();
	
	/**
	 * 模板文件目录
	 *
	 * @var unknown
	 */
	private $_html_dir;
	
	/**
	 * 编译文件目录
	 *
	 * @var unknown
	 */
	private $_compiled_dir;
	
	/**
	 * 缓存文件目录
	 *
	 * @var unknown
	 */
	private $_cache_dir;
	
	/**
	 * 模板目录
	 *
	 * @var unknown
	 */
	public $_path;
	
	/**
	 * 控制器名称
	 *
	 * @var string
	 */
	public $_actionName = null;

    /**
     * 设置目录
     * @var null
     */
    public $_dir = null;

    /**
     * 设置段落
     * @var array
     */
    public $_section = array();


	/**
	 * 设置模板路径
	 *
	 * @param string $path        	
	 */
	public function setPath($path = null) {
		if ($path) {
			$this->_path =  $path . '/';
		} else {
            throw new UpaddException('找不到模板目录',404);
		}
	}

    /**
     * 设置目录
     * @param $dirPath
     */
    public function setDir($dirPath){
        if (! empty ( $dirPath )) {
            $this->_dir = UPADD_HOST . $dirPath . '/';
        } else {
            throw new UpaddException('找不到模板目录',404);
        }
    }


    /**
     * 判断模板文件
     * @param $file
     * @return string
     */
    protected function isHtmlFile($file){
        $_data = '';
        if($this->_dir){
            $_data = $this->_dir . $this->_path . $file;
        }else{
            $_data = UPADD_HOST.'/works/view/'. $this->_path . $file;
        }
        return $_data;
    }


    /**
     * 数据文件
     * @param $file
     * @param $cache
     * @throws UpaddException
     */
    protected function createDataFile($file,$cache){
        //编译目录
        if(self::checkPath ( HTML_COMPILED_DIR.$this->_path)){
            $this->_compiled = HTML_COMPILED_DIR.$this->_path.md5 ( $file ) . $file . '.php';
        }

        if($cache && self::checkPath(HTML_CACHE_DIR .$this->_path)) {
            $this->_cache = HTML_CACHE_DIR.$this->_path. md5($file) . $file . '.html';
        }
    }


    /**
     * 模板编译
     */
    protected function getComilled() {
        if (HTML_TAG) {
            //$this->pregstyle();
            $this->pregVal ();
            $this->load();
        }

        if (! file_put_contents ( $this->_compiled, $this->_fileVar )) {
            throw new UpaddException('编译后的文件产生的错误' . $this->_htmlFile,404);
        }
    }

    /**
     * 模板缓存
     */
    protected function getCache() {
        if (! file_exists ( $this->_cache ) || filemtime ( $this->_cache ) < filemtime ( $this->_compiled )) {
            if (HTML_IS_CACHE) {
                file_put_contents ( $this->_cache, ob_get_contents () );
                ob_end_clean ();
                include $this->_cache;
            }
        }
    }

    /**
     * 判断目录是否存在，如果不存在就创建
     *
     * @param unknown $path
     */
    private function checkPath($path) {
        // 设置总目录
        if (! is_dir ( $path ) || ! is_writeable ( $path )) {
            if (! mkdir ( $path, 0777 )) {
                throw new UpaddException(lang ( 'is_dir_html' ),404);
            }
        }

        return true;
    }

    // arr
    private function pregVal() {
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
        $this->_fileVar = preg_replace ( $preaa, $prebb, $this->_fileVar );
        if (preg_match ( $preaa [0], $this->_fileVar )) {
            $this->_fileVar = $this->setArr ( $this->_fileVar );
        }
    }

    public function load()
    {
        $preg = array();
        $val = array();
        $key = 'load';
        if($preg[] = "/\@{$key}\(\'(.*?)\'\)/i")
        {
            $val[] = '<?php include UPADD_HOST."$1"; ?>';
        }

        $this->_fileVar = preg_replace ( $preg, $val, $this->_fileVar );
    }

    /**
     * 前端资源
     */
    public function pregStyle(){

        $preg = array();
        $val = array();
        $key = array('public_js','css','plug_js','plug_css');
        foreach ($key as $k=>$v){
            $preg[] = "/\@{$v}\(\'(.*?)\'\)/i";
            switch ($v){
                case 'css':
                    $public_css = conf('conf@site').'/resou/css/'."$1";
                    $val[] = '<link rel="stylesheet" href='.$public_css.'>';
                    break;

                case 'public_js':
                    $val[] = '<script type="text/javascript" href="$1"></script>';
                    break;
            }
        }

        $this->_fileVar = preg_replace ( $preg, $val, $this->_fileVar );
    }


	/**
	 * 模板变量
	 * @param string $key        	
	 * @param string $_value        	
	 */
	public function val($key, $value) {
		if (isset ( $key ) && ! empty ( $key )) {
			$this->_keyArr [$key] = $value;
		} else {
            throw new UpaddException('Please set your value!',404);
		}
	}
	
	/**
	 * 指向模板
	 *
	 * @param string $_File        	
	 */
	public function path($file = '', $cache = 0) {
		if ($cache) {
			HTML_IS_CACHE ? ob_start () : null;
		}
		
		extract ( $this->_keyArr );

        $this->_htmlFile = $this->isHtmlFile($file);

        // 判断模板是否存在
        if (! file_exists ( $this->_htmlFile )) {
            throw new UpaddException('模板文件不存在' . $this->_htmlFile,404);
        }

        // 赋值和判断读取
        if (! $this->_fileVar = file_get_contents ( $this->_htmlFile )) {
            throw new UpaddException('模板文件读取错误' . $this->_htmlFile,404);
        }

        //创建数据文件
        $this->createDataFile($file,$cache);

		// 判断编译文件是否更新
		if (! file_exists ( $this->_compiled ) || filemtime ( $this->_compiled ) < filemtime ( $this->_htmlFile )) {
			$this->getComilled ();
		}

		// 引入编译文件
		include $this->_compiled;
		// 模板缓存
		$this->getCache ();
	}




}