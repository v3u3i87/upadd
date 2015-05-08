<?php

namespace Upadd\Bin\View\Templates;
/**
+----------------------------------------------------------------------
| UPADD [ Can be better to Up add]
+----------------------------------------------------------------------
| Copyright (c) 20011-2015 http://upadd.cn All rights reserved.
+----------------------------------------------------------------------
| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
+----------------------------------------------------------------------
| Author: Richard.z <v3u3i87@gmail.com>
 **/

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

	private static $_instance = null;
	
	// 公共静态方法获取实例化的对象
	static public function getHtml() {
		if (! (self::$_instance instanceof self)) {
			self::$_instance = new self ();
		}
		return self::$_instance;
	}
	
	// 私有克隆
	final private function __clone() {}

	public function __construct() {}

	/**
	 * 设置模板路径
	 *
	 * @param string $path        	
	 */
	public function setPath($path = null) {
		if (! empty ( $path )) {
			$this->_path = HTML_PAHT . $path . '/';
		} else {
			exit ( "找不到模板路径" );
		}
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
			exit ( 'Please set your value!' );
		}
	}
	
	/**
	 * 指向模板
	 *
	 * @param string $_File        	
	 */
	public function view($file = '', $cache = 0) {
		if ($cache) {
			HTML_IS_CACHE ? ob_start () : null;
		}
		
		extract ( $this->_keyArr );
		
		// 判断目录是否存在，如果不存在就根据访问路径创建
		if (! isset ( $this->_path ) && ! is_dir ( $this->_path )) {
			$this->_htmlFile = HTML_PAHT . $this->_actionName . '/' . $file;
		} else {
			$this->_htmlFile = $this->_path . $file;
		}

		// 判断编译目录
		self::checkPath ( HTML_COMPILED_DIR );
		$this->_compiled = HTML_COMPILED_DIR . md5 ( $file ) . $file . '.php';

		if($cache) {
            // 判断缓存目录
            self::checkPath(HTML_CACHE_DIR);
            $this->_cache = HTML_CACHE_DIR . md5($file) . $file . '.html';
        }

		// 判断模板是否存在
		if (! file_exists ( $this->_htmlFile )) {
			exit ( '模板文件不存在' . $this->_htmlFile );
		}
		
		// 赋值和判断读取
		if (! $this->_fileVar = file_get_contents ( $this->_htmlFile )) {
			exit ( '模板文件读取错误!' );
		}
		
		// 判断编译文件是否更新
		if (! file_exists ( $this->_compiled ) || filemtime ( $this->_compiled ) < filemtime ( $this->_htmlFile )) {
			$this->setComilled ();
		}
		// 引入编译文件
		include $this->_compiled;
		// 模板缓存
		$this->setCache ();
	}
	
	/**
	 * 模板编译
	 */
	public function setComilled() {
		if (HTML_TAG) {
            $this->pregstyle();
			$this->pregVal ();
		}

		if (! file_put_contents ( $this->_compiled, $this->_fileVar )) {
			exit ( '编译后的文件产生的错误!' );
		}
	}
	
	/**
	 * 模板缓存
	 */
	private function setCache() {
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
				is_exit ( lang ( 'is_dir_html' ) );
			}
		}
	}
	
	// arr
	private function pregVal() {
		$preaa = array (
				'/<\!--\s\$([\w]+)\s\-->/',
				'/<\!--\s+if\s+\$([\w]+)\s+\-->/',
				'/<\!--\s+\/if\s+\-->/',
				'/<\!--\s+else\s+\-->/',
				'/<\!--\s+loop\s+\$([\w]+)\(([\w]+),([\w]+)\)\s+\-->/',
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


    /**
     * 前端资源
     */
    public function pregStyle(){
        $preg = array();
        $val = array();
        $key = array('js','css');
        foreach ($key as $k=>$v){
            $preg[] = "/\@{$v}\(\'(.*?)\'\)/i";
            switch ($v){
                case 'css':
                    $val[] = '<link rel="stylesheet" type="text/css" href="$1">';

                    break;

                case 'js':
                    $val[] = '<script type="text/javascript" href="$1"></script>';
                    break;
            }
        }
        $this->_fileVar = preg_replace ( $preg, $val, $this->_fileVar );
    }

}