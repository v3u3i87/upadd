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

namespace Upadd\Bin\Tool;

/**
 * 安全检查函数
 */
class Security {

	CONST SALT = "secret-salt";

	static $ttl = 7200;
	
	/**
	 * 加密字符串
	 *
	 * @param string $data        	
	 * @return string
	 */
	public static function challenge($data) {
		return hash_hmac ( 'md5', $data, self::SALT );
	}
	
	/**
	 * 生成令牌
	 *
	 * @param string $key
	 *        	可传用户uid, 用户名称状态等
	 * @return string
	 */
	public static function issueCrumb($key = '') {
		$i = ceil ( time () / self::$ttl );
		return substr ( self::challenge ( $i . $key ), - 12, 10 );
	}
	
	/**
	 * 验证令牌
	 *
	 * @param string $crumb        	
	 * @param string $key        	
	 * @return BOOL
	 */
	public static function verifyCrumb($crumb, $key = '') {
		$i = ceil ( time () / self::$ttl );
		
		if (substr ( self::challenge ( $i . $key ), - 12, 10 ) == $crumb || substr ( self::challenge ( ($i - 1) . $key ), - 12, 10 ) == $crumb)
			return TRUE;
		
		return FALSE;
	}
	
	/**
	 * 过滤相关提交数据
	 *
	 * @param
	 *        	string| array $contents
	 * @return string
	 */
	public static function filterContents($content, $striptags = false) {
		if (is_array ( $content )) {
			foreach ( $content as $key => $value ) {
				$content [$key] = self::filterContents ( $value, $striptags );
			}
			return $content;
		} else if (is_int ( $content )) {
			return $content;
		}
		
		$content = trim ( $content );
		if ($striptags) {
			return strip_tags ( $content );
		}
		// $content = stripslashes($content);
		$patterns = array (
				'/&#[xX\d]/i',
				'/<script.*\/script>/is',
				'/\bon[a-z]+\s*=\s*("[^"]+"|\'[^\']+\'|[^\s]+)/i',
				'/javascript:.+/i',
				'/vbscript:.+/i',
				'/:expression.+/i',
				'/@import.+/i',
				'/<meta\s+(.*?)>/is',
				'/<object\s+(.*?)>/is',
				'/<iframe\s+(.*?)>/is',
				'/\/\*/is',
				'/\*\//is' 
		);
		foreach ( $patterns as $pattern ) {
			$content = preg_replace ( $pattern, '', $content );
		}
		return $content;
	}
	
	/**
	 * 检查是否含有危险HTML代码
	 *
	 * @param obj $contents        	
	 * @return BOOL
	 */
	public static function checkContents($content) {
		if (empty ( $content ))
			return TRUE;
		if (is_array ( $content )) {
			foreach ( $content as $value ) {
				if (! self::checkContents ( $value ))
					return FALSE;
			}
			return TRUE;
		} else if (is_int ( $content )) {
			return TRUE;
		}
		$content = trim ( $content );
		// $content = stripslashes($content);
		$patterns = array (
				'/&#[xX\d]/i',
				'/<script.*\/script>/is',
				'/\bon[a-z]+\s*=\s*("[^"]+"|\'[^\']+\'|[^\s]+)/i',
				'/javascript:.+/i',
				'/vbscript:.+/i',
				'/:expression.+/i',
				'/@import.+/i',
				'/<meta\s+(.*?)>/is',
				'/<object\s+(.*?)>/is',
				'/<iframe\s+(.*?)>/is',
				'/\/\*/is',
				'/\*\//is' 
		);
		foreach ( $patterns as $pattern ) {
			if (preg_match ( $pattern, $content )) {
				return FALSE;
			}
		}
		return TRUE;
	}
	
	/**
	 * 检查来源
	 *
	 * @param BOOL $isallowout        	
	 * @param array $allowlist
	 *        	允许地址列表
	 * @return BOOL
	 */
	public static function checkRefer($isallowout = FALSE, $allowlist = array()) {
		$referer = '';
		if (isset ( $_SERVER )) {
			if (isset ( $_SERVER ['HTTP_REFERER'] )) {
				$referer = $_SERVER ['HTTP_REFERER'];
			} else {
				return FALSE;
			}
		} else {
			if (getenv ( "HTTP_REFERER" )) {
				$referer = getenv ( "HTTP_REFERER" );
			} else {
				return FALSE;
			}
		}
		if ($isallowout) {
			return TRUE;
		} else {
			if (! empty ( $allowlist ) && is_array ( $allowlist )) {
				foreach ( $allowlist as $url ) {
					$refererurl = parse_url ( $referer );
					if ($refererurl) {
						if (strpos ( $url, $refererurl ['host'] ) !== FALSE) {
							return TRUE;
						}
					}
				}
			}
		}
		return FALSE;
	}

	public static function getAgent() {
		$agent = '';
		if (isset ( $_SERVER )) {
			$agent = $_SERVER ['HTTP_USER_AGENT'];
		} else {
			$agent = getenv ( "HTTP_USER_AGENT" );
		}
		if (stristr ( $agent, "MSIE 8.0" ))
			$agent = "Internet Explorer 8.0";
		else if (stristr ( $agent, "MSIE 7.0" ))
			$agent = "Internet Explorer 7.0";
		else if (stristr ( $agent, "MSIE 6.0" ))
			$agent = "Internet Explorer 6.0";
		else if (stristr ( $agent, "Firefox/3" ))
			$agent = "Firefox 3";
		else if (stristr ( $agent, "Firefox/2" ))
			$agent = "Firefox 2";
		else if (stristr ( $agent, "Chrome" ))
			$agent = "Google Chrome";
		else if (stristr ( $agent, "Safari" ))
			$agent = "Safari";
		else if (stristr ( $agent, "Opera" ))
			$agent = "Opera";
		else
			$agent = NULL;
		
		return $agent;
	}
	
	/**
	 * 安全检查
	 *
	 * @return BOOL
	 */
	public static function checkSafe() {
		if (self::checkRefer ()) {
			if (! is_null ( self::getAgent () )) {
				if (isset ( $_REQUEST ['crumb'] )) {
					if (self::verifyCrumb ( $_REQUEST ['crumb'] )) {
						$arr = $_SERVER + $_REQUEST;
						if (self::checkContents ( $arr )) {
							return TRUE;
						}
					}
				}
			}
		}
		return FALSE;
	}


}