<?php
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

namespace Upadd\Bin;

/**
 * 日记处理类
 * 静态方法调用DataLog::write('内容',预创建的文件名称)
 * @author Richard.z
 */
class Log {
	
	/**
	 * 启用记录
	 *
	 * @param 内容 $cont        	
	 * @param 文件名称以及格式 $file        	
	 */
	public static function write($cont, $fileName) {
		if (APP_IS_LOG) {
			$info = 'URL:' . $_SERVER ['REQUEST_URI'] . "\r\n";
			$info .= 'Time: ' . date ( "Y-m-d H:i:s" ) . "\r\n";
			$info .= 'Info:' . $cont . "\r\r\r";
			$file = self::isBak ( $fileName );
			$fh = fopen ( $file, 'a+' );
			fwrite ( $fh, $info );
			fclose ( $fh );
		}
	}
	
	/**
	 * 验证文件大小
	 *
	 * @param unknown $file        	
	 * @return string
	 */
	private static function isBak($file) {
		$log = self::getPath () . $file;
		if (! file_exists ( $log )) {
			touch ( $log );
			return $log;
		}
		
		$size = filesize ( $log );
		if ($size <= 1024 * 1024) {
			return $log;
		}
		
		// 如果不存在就创建
		if (! self::bak ( $file )) {
			return $log;
		} else {
			touch ( $log );
			return $log;
		}
	} // end is bak
	
	/**
	 * 执行备份
	 *
	 * @param unknown $file        	
	 * @return boolean
	 */
	private static function bak($file) {
		$log = self::getPath () . $file;
		$bak = self::getPath () . date ( 'Y-m-d_H-i-s' ) . '_' . $file . '_' . mt_rand ( 1, 9999 ) . '.log';
		return rename ( $log, $bak );
	}
	
	/**
	 * 获取路径
	 *
	 * @param string $path        	
	 * @return string
	 */
	private static function getPath() {
		self::checkPath ( LOG_PATH );
		return LOG_PATH;
	}
	
	/**
	 * 检查目录
	 *
	 * @param unknown $path        	
	 */
	private static function checkPath($path) {
		// 设置总目录
		if (! is_dir ( $path ) || ! is_writeable ( $path )) {
			if (! mkdir ( $path, 0777 )) {
				is_exit ( lang ( 'is_dir_log' ) );
			}
		}
	}
}
