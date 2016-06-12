<?php
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
namespace Upadd\Bin\Tool;


use Config;
use Upadd\Bin\UpaddException;

class Log {


	/**
	 * 全局笔记
	 * @param array $cont
	 * @param string $fileName
	 */
	public function notes($cont=array(),$fileName='notes.logs')
    {

        if(is_array($cont) || is_object($cont))
        {
            $info = json($cont) . "\r\n";
        }elseif(is_string($cont))
        {
            $info = $cont . "\r\n";
        }

		$info .= 'Time: ' . date ( "Y-m-d H:i:s" ) . "\r\n";
		$file = self::isBak ( $fileName );
		$fh = fopen ( $file, 'a+' );
		fwrite ( $fh, $info );
		fclose ( $fh );
	}





	/**
	 * 启用记录
	 * @param 内容 $cont        	
	 * @param 文件名称以及格式 $file        	
	 */
	public static function write($cont, $fileName) {
        $info = 'URL:' . self::getHttpUrl() . "\r\n";
        $info .= 'Time: ' . date ( "Y-m-d H:i:s" ) . "\r\n";
        $info .= $cont . "\r\r\r";
        $file = self::isBak ( $fileName );
        $fh = fopen ( $file, 'a+' );
        fwrite ( $fh, $info );
        fclose ( $fh );
	}



    /**
     * 获取请求记录
     * @param 内容 $cont
     * @param 文件名称以及格式 $file
     */
    public static function request($cont, $fileName = 'request.logs') {
        $cont['url'] = self::getHttpUrl();
        $cont['time'] = date ( 'Y-m-d H:i:s' );
        $info = json($cont) . "\r\r";
        $file = self::isBak ( $fileName );
        $fh = fopen ( $file, 'a+' );
        fwrite ( $fh, $info );
        fclose ( $fh );
    }


	/**
	 * 验证文件大小
	 *
	 * @param unknown $file        	
	 * @return string
	 */
	private static function isBak($file)
	{
		$log = self::getPath () . $file;
		if (! file_exists ( $log ))
		{
			touch ( $log );
			return $log;
		}
		
		$size = filesize ( $log );
		/**
		 * 判断是否大于1G
		 */
		if ($size <= 1099511627776)
		{
			return $log;
		}
		
		// 如果不存在就创建
		if (! self::bak ( $file ))
		{
			return $log;

		} else {
			touch ( $log );
			return $log;
		}

	} // end is bak



	/**
	 * 执行备份
	 * @param unknown $file        	
	 * @return boolean
	 */
	private static function bak($file)
	{
		$log = self::getPath () . $file;
		$bak = self::getPath () . date ( 'Y-m-d_H-i-s' ) . '_' . $file . '_' . mt_rand ( 1, 9999 ) . '.log';
		return rename ( $log, $bak );
	}
	
	/**
	 * 获取路径
	 * @param string $path        	
	 * @return string
	 */
	private static function getPath()
	{
        $logPath = Config::get('sys@log_path');
		self::checkPath ( $logPath );
		return $logPath;
	}
	
	/**
	 * 检查目录
	 *
	 * @param unknown $path        	
	 */
	private static function checkPath($path)
	{
		//设置总目录
		if (!is_dir($path) || !is_writeable($path))
		{
			if (! mkdir ( $path, 0777 ))
			{
                throw new UpaddException('日记目录异常',404);
			}
		}
	}

	/**
	 * 获取访问路径
	 * @return string
	 */
	protected static function getHttpUrl()
	{
		if(is_run_evn())
		{
			if(isset($_SERVER ['REQUEST_URI'])) return $_SERVER ['REQUEST_URI'];
		}else{
			return 'cli';
		}
	}




}
