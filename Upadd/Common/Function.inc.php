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

//Upadd函数库

/**
 * 该函数格式化打印数组
 * @param unknown $data
 * @param string $type true as 1 为不断点执行
 */
function p($data,$type=''){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	if(!$type)  exit(); 
}

/**
 *  打印参数详细数据 var_dump
 * @param unknown $data
 * @param string $type true as 1 为不断点执行
 */
function vd($data,$type=''){
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
	if(!$type)  exit();
}


/**
 * 转跳
 * @param string $url
 */
function AskJump($url){
	if(isset($url)){
		header('Location:'.$url);
		exit;
	}
}



/**
 * 分割数组或字符串处理
 * @param string $type : , | @ 
 * @param type $data : array|string
 * @internal string  $type ->a=array ->explode || $type ->s=string ->implode
 * @return array|string
 */
function lode($type,$data){
	if(is_string($data)){
		return explode($type,$data);
	}elseif (is_array($data)){
		return implode($type,$data);
	}
}

/**
 * 多维数组合并成一维数组
 * @param unknown $a
 * @return unknown
 */
function array_merge_one($data){
	static $one;
	foreach($data as $v){is_array($v) ? array_merge_one($v) : $one[]=$v ;}
	return $one;
}

/**
 *  * 加载拓展类
 * @param string $class
 * @param string $type as api
 */
function Load($class){
	if(isset($class)){
		$file = IS_EXPAND. str_replace('_', DIRECTORY_SEPARATOR, $class) . '.class' . IS_EXP;
		if(is_file($file)){
			require $file;
		}else{
			exit('文件不存在');
		}
	}
	
}

/**
 * 加载第三方API
 * @param unknown $class
 */
function loadApi($class){
	if(isset($class)){
		$file = UPADD_API. str_replace('_', DIRECTORY_SEPARATOR, $class) . '.class' . IS_EXP;
		if(is_file($file)){
			require $file;
		}else{
			exit('文件不存在');
		}
	}
}

/**
 * 加载前端静态文件
 * @param string $file
 */
function LoadUed($file='',$type){
	if(isset($type['path']) && !empty($type['path'])){
		//拼凑载入文件路径
		$plug = is_conf('setStyle').STYLE_PATH.$type['path'].'/'.$file;
	}else{
		//引入插件
		$plug = is_conf('setStyle').STYLE_PATH.'plug/'.$file;
	}
	//载入静态资源文件
	if($type['plug']=='css'){
		echo '<link rel="stylesheet"  href="'.$plug.'" />';
	}elseif ($type['plug']=='js'){
		echo '<script type="text/javascript" src="'.$plug.'" ></script>';
	}
}

/**
 * 加载图片
 * @param string $file
 * @param unknown $type
 */
function LoadImg($file='',$type){
	if(isset($type['path']) && !empty($type['path'])){
		//拼凑载入文件路径
		$plug = is_conf('setStyle').STYLE_PATH.$type['path'].'/'.$file;
	}
	//载入静态资源文件
	if($type['type']=='img'){
		echo '<img src="'.$plug.'">';
	}elseif ($type['type']==='set' && !empty($type['set'])){
		echo '<img src="'.$plug.'" '.$type['set'].' >';
	}
}

/**
 * 加载HTML文件
 * @param string $_file
 */
function LoadHtml($file='',$type=''){
	$_file = UPADD_HOST.APP_PAHT.'Html/'.$file;
	if(is_file($_file) && empty($type)){
		include $_file;
	}else if(is_file($_file) && $type){
		return $_file;
	}
}



/**
 * 获取错误信息
 * @param unknown $error
 */
function get_debug($error=null) {
	$err = array();
	if (APP_DEBUG) {
		//调试模式下输出错误信息
		if (!is_array($error)) {
			$trace          			= debug_backtrace();
			$err['message']   	= $error;
			$err['file']      		= $trace[0]['file'];
			$err['line']      		= $trace[0]['line'];
			ob_start();
			debug_print_backtrace();
			$err['trace']     = ob_get_clean();
		} else {
			$err              = $error;
		}
	} 
	p($err);
}

/**
 * 自定义异常处理
 * @param string $msg 异常消息
 * @param string $type 异常类型 默认为UpaddException
 * @param integer $code 异常代码 默认为0
 * @return void
 */
function throw_exception($msg, $type='UpaddException', $code=0) {
	if (class_exists($type, false))
		throw new $type($msg, $code);
	else
		get_debug($msg);        // 异常类型不存在则输出错误信息字串
}


/**
 * 序列化函数
 * @param 序列化的数据 $data
 * @param 类型 $type as true 序列化 | false 反序列化
 * @return string|mixed
 */
function setSequence($data,$type){
	if($type){
		return serialize($data);
	}elseif(!$type){
		return unserialize($data);
	}
}

/**
 * 调用配置数组参数
 * @param string $name
 * @param string $val
 * @return string
 */
function is_conf($name='',$val=''){
	$conf = Conf::getConf();
	if(isset($name) && !empty($val)){
		return $conf->$name = $val;
	}else{
		return $conf->$name;
	}
}


/**
 * 获取_get数据
 * @param string || array $name
 */
function call($name=null,$val=null){
	$http = Http::getHttp();
	return $http->getParam($name);
}


/**
 * 获取HTTP请求类
 * @return Http
 */
function h(){
	return Http::getHttp();
}


/**
 * 设置 session
 * @param string $key
 * @param string $val
 * @return string
 */
function s($key,$val=null){
	if(is_string($key)){
		if(!empty($val)){
			$_SESSION[$key]=$val;
		}else{
			return $_SESSION[$key];
		}
	}
}


/**
 * 导出Excel
 * @param unknown $filename
 * @param unknown $content
 * @Demo
 * 		$str = "任务标题\t任务地址\t创建时间\t\n";
		$str = iconv('utf-8','gb2312',$str);
		foreach ($this->model->setTaskExcel() as $k=>$v){
			$title = iconv('utf-8','gb2312',$v['task_title']);
			$url = iconv('utf-8','gb2312',$v['task_url']);
			$str .= $title."\t".$url."\t".date('Y-m-d H:s:i',$v['create_time'])."\t\n";
		}
		$filename = $_SESSION['m_site'].date('Ymd').'.xls';
		setExcel($filename,$str);
 */
function setExcel($filename,$content){
	if(isset($filename) && isset($content)){
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/vnd.ms-execl");
		header("Content-Type: application/force-download");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment; filename=".$filename);
		header("Content-Transfer-Encoding: binary");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $content;
	}
}

/**
 * 令牌
 */
function is_upToken(){defined('UPADD_HOST') or exit('Please visit the normal!');}




