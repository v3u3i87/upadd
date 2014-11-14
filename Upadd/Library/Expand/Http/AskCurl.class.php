<?php
defined('UPADD_HOST') or exit();
//CURL类
class AskCurl{
	//用于存放实例化的对象
	static private $_upadd = null;

	//公共静态方法获取实例化的对象
	static public function getAsk() {
		if (!(self::$_upadd instanceof self)) {self::$_upadd = new self();}return self::$_upadd;
	}
	
	//私有克隆
	private function __clone() {}
	
	//私有构造
	private function __construct() {}
	
	/**
	 * 模拟URL提交
	 * @param string $data 提交数据
	 * @param string $url 提交URL地址
	 * @param string $type  提交类型 GET或POST
	 * @return string|mixed
	 */
	public static function SendCurl($data='',$url='',$type=''){
		if(isset($data) && isset($url) && isset($type)){
			//初始化
			$ch = curl_init();
			//设置选项，包括URL
			curl_setopt($ch, CURLOPT_URL, $url);//url
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			$User_Agen = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36';
			curl_setopt($ch, CURLOPT_USERAGENT, $User_Agen);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//数据
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//执行并获取HTML文档内容
			$info = curl_exec($ch);
			if (curl_errno($ch)) {
				return curl_error($ch);
			}
			//释放curl句柄关闭
			curl_close($ch);			
			$info = self::getInJson(array('type'=>'json','json'=>$info));
			return $info;
		}
	}
	
	/**
	 * 获取json信息转数组
	 * @param string $String
	 * @return mixed
	 */
	public function getInJson($info=''){
		if(isset($info) && is_array($info)){
			switch ($info['type']){
				case "file":
					$json = file_get_contents($info['file']);
					$msg = json_decode($json,true);//1 or true
					return $msg;
					break;
				case "json":
					$msg = json_decode($info['json'],true);//1 or true
					return $msg;
					break;
			}
		}
	}
	
	
}
