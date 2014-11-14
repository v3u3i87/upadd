<?php
/**
 +----------------------------------------------------------------------
 | UPADD [ Can be better to Up add]
 +----------------------------------------------------------------------
 | Copyright (c) 20011-2014 http://upadd.cn All rights reserved.
 +----------------------------------------------------------------------
 | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 +----------------------------------------------------------------------
 | Author: Richard.z <v3u3i87@gmail.com>
 **/
is_upToken();
/**
 * 过滤或是转移处理器
 * @author Administrator
 *
 */
class Change{
	
	//表单提交字符转义
	static public function GetFormString($_string) {
		if (!get_magic_quotes_gpc()) {
			if (Verify::IsArr($_string)) {
				foreach ($_string as $_key=>$_value) {
					$_string[$_key] = self::GetFormString($_value);	//不支持就用代替addslashes();
				}
			} else {
				return addslashes($_string); //mysql_real_escape_string($_string, $_link);
			}
		}
		return $_string;
	}
	
	//html过滤 htmlentities
	static public function GetHtmlString($_data) {
		$_string = '';
		if (Verify::isArr($_data)) {
			if (Verify::isNullArray($_data)) return $_data;
			foreach ($_data as $_key=>$_value) {
				$_string[$_key] = self::GetHtmlString($_value);  //递归
			}
		} elseif (is_object($_data)) {
			foreach ($_data as $_key=>$_value) {
				$_string->$_key = self::GetHtmlString($_value);  //递归
			}
		} else {
			$_string = htmlspecialchars($_data);
		}
		return $_string;
	}
	
	//过滤
	static public function setGetPost() {
		if (!isset($_GET)) $_GET = self::GetFormString($_GET);
		if (!isset($_POST)) $_POST = self::GetFormString($_POST);
	}
	
	
	
}