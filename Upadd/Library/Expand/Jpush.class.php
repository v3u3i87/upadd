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
//极光推送类
class Jpush{
	
		private $_masterSecret = '';
		private $_appkeys = '';
	
	/**
	 * 构造函数
	 * @param string $username
	 * @param string $password
	 * @param string $appkeys
	 */
	public function __construct($masterSecret = '',$appkeys = '') {
		$this->_masterSecret = $masterSecret;
		$this->_appkeys = $appkeys;
	}
	
	/**
	 * 模拟post进行url请求
	 * @param string $url
	 * @param string $param
	 */
	public function request_post($url = '', $param = '') {
			if (empty($url) || empty($param)) {
				return false;
			}
			$postUrl = $url;
			$curlPost = $param;
			$ch = curl_init();//初始化curl
			curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
			curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
			curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
			curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
			$data = curl_exec($ch);//运行curl
			curl_close($ch);
			return $data;
	}
	
	/**
	 * 发送
	 * @param int $sendno 发送编号。由开发者自己维护，标识一次发送请求
	 * @param int $receiver_type 接收者类型。1、指定的 IMEI。此时必须指定 appKeys。2、指定的 tag。3、指定的 alias。4、 对指定 appkey 的所有用户推送消息。
	 * @param string $receiver_value 发送范围值，与 receiver_type相对应。 1、IMEI只支持一个 2、tag 支持多个，使用 "," 间隔。 3、alias 支持多个，使用 "," 间隔。 4、不需要填
	 * @param int $msg_type 发送消息的类型：1、通知 2、自定义消息
	 * @param string $msg_content 发送消息的内容。 与 msg_type 相对应的值
	 * @param string $platform 目标用户终端手机的平台类型，如： android, ios 多个请使用逗号分隔
	 */
	public function send($sendno = 0,$receiver_type = 1, $receiver_value = '', $msg_type = 1, $msg_content = '', $platform = 'android,ios') {
			$url = 'http://api.jpush.cn:8800/sendmsg/v2/sendmsg';
			$param = '';
			$param .= '&sendno='.$sendno;
			$appkeys = $this->_appkeys;
			$param .= '&app_key='.$appkeys;
			$param .= '&receiver_type='.$receiver_type;
			$param .= '&receiver_value='.$receiver_value;
			$masterSecret = $this->_masterSecret;
			$verification_code = md5($sendno.$receiver_type.$receiver_value.$masterSecret);
			$param .= '&verification_code='.$verification_code;
			$param .= '&msg_type='.$msg_type;
			$param .= '&msg_content='.$msg_content;
			//$param.='&value='.'{"type":"1"}';
			$param .= '&platform='.$platform;
			$res = $this->request_post($url, $param);
			if ($res === false) {
				return false;
			}
			$res_arr = json_decode($res, true);
			return $res_arr;
	}
	
	/**
	 * 判断错误码
	 */
	public function is_errmsg(){
		$res_arr['errmsg']= "没有错误信息";
		switch (intval($res_arr['errcode'])) {
			case 0:
				$res_arr['errmsg'] = '发送成功';
				//echo '发送成功';
				break;
			case 10:
				$res_arr['errmsg'] = '系统内部错误';
				//echo '系统内部错误';
				break;
			case 1001:
				$res_arr['errmsg'] = '只支持 HTTP Post 方法，不支持 Get 方法';
				//echo '只支持 HTTP Post 方法，不支持 Get 方法';
				break;
			case 1002:
				$res_arr['errmsg'] = '缺少了必须的参数';
				//echo '缺少了必须的参数';
				break;
			case 1003:
				$res_arr['errmsg'] = '参数值不合法';
				//echo '参数值不合法';
				break;
			case 1004:
				$res_arr['errmsg'] = '验证失败';
				//echo '验证失败';
				break;
			case 1005:
				$res_arr['errmsg'] = '消息体太大';
				//echo '消息体太大';
				break;
			case 1007:
				$res_arr['errmsg'] = 'receiver_value 参数 非法';
				//echo 'receiver_value 参数 非法';
				break;
			case 1008:
				$res_arr['errmsg'] = 'appkey参数非法';
				//echo 'appkey参数非法';
				break;
			case 1010:
				$res_arr['errmsg'] = 'msg_content 不合法';
				//echo 'msg_content 不合法';
				break;
			case 1011:
				$res_arr['errmsg'] = '没有满足条件的推送目标';
				//echo '没有满足条件的推送目标';
				break;
			case 1012:
				$res_arr['errmsg'] = 'iOS 不支持推送自定义消息。只有 Android 支持推送自定义消息';
				//echo 'iOS 不支持推送自定义消息。只有 Android 支持推送自定义消息。';
				break;
			default:
				//echo '调用成功';
				break;
		}
	}
}
