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
use Upadd\Bin\View\Tag;

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
	public $_keyArr = array ();
	

	/**
	 * 模板目录
	 *
	 * @var unknown
	 */
	public $_path;

    /**
     * 设置特定的路径
     * @var null
     */
    public $_setPath = null;

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
     * 判断模板文件
     * @param $file
     * @return string
     */
    protected function isHtmlFile($file)
    {
        if($this->_setPath)
        {
            $this->_htmlFile = $this->_setPath.'/'.$file;
        }else{
            $this->_htmlFile = $this->_path.'/'.$file;
        }
        if(!file_exists($this->_htmlFile))
        {
            throw new UpaddException('您的'.$file.'模板文件不存在!');
        }
    }

    /**
     * 数据文件
     * @param $file
     * @param $cache
     * @throws UpaddException
     */
    protected function createDataFile($file,$cache)
    {
        $dirCompiled = host().'data/'.APP_NAME . '/compiled/'.$this->_actionName;
        //编译目录
        if(is_create_dir ($dirCompiled))
        {
            $this->_compiled = $dirCompiled.'/'.md5 ( $file ) . $file . '.php';
        }
        $dirCache = host().'data/'.APP_NAME . '/cache/'.$this->_actionName;
        if($cache && is_create_dir($dirCache))
        {
            $this->_cache = $dirCache.'/'.md5($file) . $file . '.html';
        }
    }

    /**
     * 模板编译
     */
    protected function getComilled()
    {
        /**
         * 更新编译模板文件
         */
        if (! file_put_contents ( $this->_compiled, $this->_fileVar ))
        {
            throw new UpaddException('编译后的文件产生的错误' . $this->_htmlFile,404);
        }
    }

    /**
     * 模板缓存
     */
    protected function getCache()
    {
        if (! file_exists ( $this->_cache ) || filemtime ( $this->_cache ) < filemtime ( $this->_compiled ))
        {
            if (Config::get('sys@is_html_cache'))
            {
                file_put_contents ( $this->_cache, ob_get_contents () );
                ob_end_clean ();
                include $this->_cache;
            }
        }
    }


	/**
	 * 模板变量
	 * @param string $key        	
	 * @param string $_value        	
	 */
	public function val($key, $value)
    {
		if (isset ( $key ) && ! empty ( $key ))
        {
			$this->_keyArr [$key] = $value;
		} else {
            throw new UpaddException('Please set your value!',404);
		}
	}

    /**
     * 设置模板控制器
     * @param $name
     */
    private function setAutoPath()
    {
        $request = Config::get('sys@request');
        $lode = lode('\\',$request['action']);
        if(isset($lode[2]))
        {
            $name = $lode[2];
            if(substr($name, -6)=='Action')
            {
                $this->_actionName = strtolower(substr($name, 0,-6));
            }
            $this->_path = host().APP_NAME.'/view/'.$this->_actionName;
        }else{
            throw new UpaddException('控制器模板目录设置失败');
        }
    }

    /**
     * 设置路径
     * @param string $path
     */
    public function setPath($path='')
    {
        if($path)
        {
            $this->_setPath = host().APP_NAME.'/view/'.$path;
        }else{
            $this->_setPath = host().APP_NAME.'/view/public';
        }
    }


	/**
	 * 指向模板
	 * @param string $_File        	
	 */
	public function bound($file = '', $cache = false)
    {
        $this->setAutoPath();

		if ($cache)
        {
            Config::get('sys@is_html_cache') ? ob_start () : null;
		}
        extract ( $this->_keyArr );
        $this->isHtmlFile($file);

        // 赋值和判断读取
        if (! $this->_fileVar = file_get_contents ( $this->_htmlFile ))
        {
            throw new UpaddException('模板文件读取错误' . $this->_htmlFile,404);
        }

        //创建数据文件
        $this->createDataFile($file,$cache);

		// 判断编译文件是否更新
		if (! file_exists ( $this->_compiled ) || filemtime ( $this->_compiled ) < filemtime ( $this->_htmlFile ))
        {
            /**
             * 执行模板标签替换
             */
            if (Config::get('sys@is_html_tag'))
            {
                $tag = new Tag($this->_fileVar,$this->_keyArr,$this->_actionName);
                $this->_fileVar = $tag->Compile();
            }
            /**
             * 更新编译文件
             */
			$this->getComilled ();
        }

		// 引入编译文件
		include $this->_compiled;
		// 模板缓存
		$this->getCache ();
	}




}