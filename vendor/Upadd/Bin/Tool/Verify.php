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

// 验证类
class Verify {
	
	// 判断是否是数组
	public static function isArr($_array)
    {
		return is_array ( $_array ) ? true : false;
	}
	
	// 判断数组是否有元素
	public static function isNullArray($_array)
    {
		return count ( $_array ) == 0 ? true : false;
	}
	
	// 判断数组是否存在此元素
	public static function InArr($_data, $_array)
    {
		return in_array ( $_data, $_array ) ? true : false;
	}
	
	// 判断字符串是否为空
	public static function IsNullString($_string)
    {
		return empty ( $_string ) ? true : false;
	}
	
	// 判断是否为数字
	public static function is_num($num)
    {
		return is_numeric ( $num ) ? true : false;
	}
	
	// 判断字符串长度是否合法
	public static function CheckStrLength($_string, $_length, $_flag, $_charset = 'utf-8')
    {
		if ($_flag == 'min') {
			if (mb_strlen ( trim ( $_string ), $_charset ) < $_length)
				return true;
			return false;
		} elseif ($_flag == 'max') {
			if (mb_strlen ( trim ( $_string ), $_charset ) > $_length)
				return true;
			return false;
		} elseif ($_flag == 'equals')
        {
			if (mb_strlen ( trim ( $_string ), $_charset ) != $_length)
            {
                return true;
            }
			return false;
		}
	}

    /**
     *  判断数据是否一致
     * @param $_string
     * @param $_otherstring
     * @return bool
     */
	public static function CheckStrEquals($_string, $_otherstring)
    {
		if (trim ( $_string ) == trim ( $_otherstring ))
        {
            return true;
        }
		return false;
	}

    /**
     * 验证输入的手机号码
     * @param $mobile
     * @return bool
     */
	public static function is_mobile($mobile)
    {
		$chars = '/^((\(\d{2,3}\))|(\d{3}\-))?1(3|5|8|9)\d{9}$/';
		if (preg_match ( $chars, $mobile ))
        {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 验证输入的邮件地址是否合法
	 *
	 * @access public
	 * @param string $user_email
	 *        	需要验证的邮件地址
	 *        	
	 * @return bool
	 */
	public static function is_email($user_email)
    {
		$chars = '/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i';
		if (strpos ( $user_email, '@' ) !== false && strpos ( $user_email, '.' ) !== false)
        {
			if (preg_match ( $chars, $user_email ))
            {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * 匹配中文，如果是中文返回true
	 * @param unknown $str        	
	 * @return boolean
	 */
	public static function is_cn($cn)
    {
		$preg = '/^[\x7f-\xff]+$/';
		if (preg_match ( $preg, $cn ) || preg_match ( '/[\x7f-\xff]/', $cn )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 匹配字符
	 *
	 * @param unknown $user_email        	
	 * @return boolean
	 */
	public static function is_zifu($user_email)
    {
		$chars = '/[\.。,，\-]/';
		if (preg_match ( $chars, $user_email ))
        {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * xss攻击检测
	 *
	 * @param unknown_type $content        	
	 * @return bool
	 */
	public static function xssCheck($content) {
		if (preg_match ( '/<script.*\/script>/is', $content )) {
			return false;
		}
		
		if (preg_match ( '/\bon[a-z]+\s*=\s*("[^"]+"|\'[^\']+\'|[^\s]+)/i', $content )) {
			return false;
		}
		
		if (preg_match ( '/\bhref=\s*("\s*javascript:[^"]+"|\'\s*javascript:[^\']+\'|javascript:[^\s]+)/i', $content )) {
			return false;
		}
		
		if (preg_match ( '/<meta\s+(.*?)>/is', $content )) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * 使用http方式请求远端数据。替代file_get_contents使用
	 *
	 * @param string $url
	 *        	远端地址
	 * @param integer $timeout
	 *        	超时，单位为秒
	 * @param
	 *        	string ref $error 错误信息。当需要时返回。
	 * @return string|false
	 */
	static public function httpGetContents($url, $timeout = 1, &$error = null) {
		$ch = curl_init ( $url );
		curl_setopt_array ( $ch, array (
				CURLOPT_CONNECTTIMEOUT => $timeout,
				CURLOPT_TIMEOUT => $timeout,
				CURLOPT_RETURNTRANSFER => TRUE 
		) );
		$result = curl_exec ( $ch );
		if (false === $result) {
			$error = curl_error ( $ch );
		}
		$status = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		// 2xx 以及 3xx 返回码认为正常。否则异常。
		if (! in_array ( intval ( $status / 100 ), array (
				2,
				3 
		) )) {
			$result = false;
			$error = 'status is ' . $status;
		}
		curl_close ( $ch );
		return $result;
	}
	
	/**
	 * 检查action名称的安全性
	 * @return bool
	 */
	static public function _checkActionName($action) {
		if (strpos ( $action, '/' ) || strpos ( $action, '.' ) || strpos ( $action, '%00' )) {
			return false;
		}
		return true;
	}


}