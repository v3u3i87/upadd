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
defined('UPADD_HOST') or exit();
//json 操作类
class Json{
	/**
	 *  使用特定function对数组中所有元素做处理 递归
	 * @param unknown $array
	 * @param unknown $function
	 * @param string $apply_to_keys_also
	 */
	private function arrJson(&$array, $function, $apply_to_keys_also = false){
		static $recursive_counter = 0;
		if (++$recursive_counter > 1000) {
			die('possible deep recursion attack');
		}
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				self::arrJson($array[$key], $function, $apply_to_keys_also);
			} else {
				$array[$key] = $function($value);
			}
	
			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
		$recursive_counter--;
	}
	
	/**
	 * 	将数组转换为JSON字符串（in gbk2312-gbk-utf-8）
	 *	@param	array	     $array	要转换的数组
	 *	@return   string		转换得到的json字符串
	 * @return   json to string in array
	 */
	static public function setJson($array) {
		self::arrJson($array, 'urlencode', true);
		$json = json_encode($array);
		return urldecode($json);
	}
	

	
}
