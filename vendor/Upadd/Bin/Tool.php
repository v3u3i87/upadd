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

// 工具类，封装函数和算法等。
namespace Upadd\Bin;

class Tool {
	
	// 获取客户端ip
	static public function getIP() {
		return $_SERVER ["REMOTE_ADDR"];
	}
	
	// 弹窗跳转
	static public function GetSucc($_info, $_url) {
		echo "<script>alert('$_info');location.href='$_url';</script>";
		exit ();
	}
	
	// 弹窗返回
	static public function GetError($_info) {
		echo "<script>alert('$_info');history.back();</script>";
		exit ();
	}
	
	// 得到上一页
	static public function GetPrevPage() {
		return empty ( $_SERVER ["HTTP_REFERER"] ) ? '###' : $_SERVER ["HTTP_REFERER"];
	}
	
	// 检查浏览器版本
	static public function bw() {
		if (strpos ( $_SERVER ["HTTP_USER_AGENT"], 'MSIE 8.0' ))
			echo 'Internet Explorer 8.0';
		else if (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'MSIE 7.0' ))
			echo 'Internet Explorer 7.0';
		else if (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'MSIE 6.0' ))
			echo 'Internet Explorer 6.0';
		else if (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Firefox/3' ))
			echo 'Firefox 3';
		else if (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Firefox/2' ))
			echo 'Firefox 2';
		else if (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Chrome' ))
			echo 'Google Chrome';
		else if (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Safari' ))
			echo 'Safari';
		else if (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'Opera' ))
			echo 'Opera';
		else
			echo $_SERVER ['HTTP_USER_AGENT'];
	}
	
	/**
	 * 设置参数
	 *
	 * @param string $data        	
	 * @return string
	 */
	public static function setUserArea($data = '') {
		if (isset ( $data ) && is_array ( $data )) {
			$p = '';
			foreach ( $data as $k => $v ) {
				$p .= $v . ',';
			}
			$p = substr ( $p, 0, - 1 );
			return $p;
		}
	}
	public static function ajaxload($url = '') {
		// 载入AJAX.JS异步加载脚本
		LoadUed ( 'ajaxfileupload.js', array (
				'plug' => 'js',
				'path' => 'js' 
		) );
		$html = '<script>
					function up(input_name,class_name,file_id){
						var phpurl = "' . $url . '";
					$.ajaxFileUpload(
						{  
						 url:phpurl,   
						 secureuri:false,  
						 fileElementId:file_id,                      
						 dataType: "json",                              
						 success: function (data, status){  
							var html ="<img src=\""+data+"\" style=\"width:150px;height:100px;\" /><input type=\'hidden\' name=\'"+input_name+"\' value=\'"+data+"\'>";
							$(class_name).html(html);
						 }
						});
					}
					</script>';
		echo $html;
	}
	public static function bdedit($edit, $js, $type = 0) {
		if ($js == 'js') {
			LoadUed ( 'ueditor.config.js', array (
					'plug' => 'js',
					'path' => 'ueditor' 
			) );
			LoadUed ( 'ueditor.all.min.js', array (
					'plug' => 'js',
					'path' => 'ueditor' 
			) );
		}
		
		if ($type) {
			$html = "
					<script>
							var editorOption = {toolbars: [[
							'bold', 
							'italic', 
							'underline',
							'fontborder',
							 'strikethrough',
							 'forecolor', 
							 'backcolor',
							// 'emotion',
						]]};
					var c = new UE.ui.Editor(editorOption);
					c.render('{$edit}');
					</script>
					";
		} else {
			$html = '<script>var c = new UE.ui.Editor();c.render("' . $edit . '");</script>';
		}
		
		echo $html;
	}
}
