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
class Upadd{
	/**
	 * 加载框架
	 */
	public static function UpStart(){
		header('X-Powered-By:'.UPADD_VERSION);
		register_shutdown_function(array('Upadd', 'FatalError'));
		set_error_handler(array('Upadd', 'AppError'));
		set_exception_handler(array('Upadd', 'AppException'));
		//类库
		self::_Run();
		//执行控制器		
		Request::getRequest();
		//程序执行完毕结束时间
		self::runTime();
	}
	
	/**
	 * 设置运行框架
	 */
	public static function _Run(){
		spl_autoload_register (array ('Upadd', 'Frame' ));
		spl_autoload_register (array ('Upadd', 'Core' ));
	}
	
	/**
	 * 调用框架
	 * @param unknown $class
	 */
	public static function Frame($class) {
		if (is_file(IS_FRAME.$class.IS_EXP)) {
			 return require IS_FRAME.$class.IS_EXP;
		}
	}

	/**
	 * 自动载入框架核心层
	 * @param unknown $_ClassName
	 */
	public static function Core($_ClassName){
		
		switch ($_ClassName){
			
			//载入核心处理类
			case file_exists(IS_CORE.$_ClassName.'.class'.IS_EXP) :
					return require IS_CORE.$_ClassName.'.class'.IS_EXP;
			break;
			
			//请求处理
			case file_exists(IS_CORE.'Http/'.$_ClassName.'.class'.IS_EXP) :
					return require IS_CORE.'Http/'.$_ClassName.'.class'.IS_EXP;
			break;
			
			//数据库处理层
			case file_exists(IS_CORE.'Db/'.$_ClassName.'.class'.IS_EXP) :
				return require IS_CORE.'Db/'.$_ClassName.'.class'.IS_EXP;
			break;
				
			//缓存处理
			case file_exists(IS_CORE.'Cache/'.$_ClassName.'.class'.IS_EXP) :
				return require IS_CORE.'Cache/'.$_ClassName.'.class'.IS_EXP;
			break;

			//Session处理
			case file_exists(IS_CORE.'Session/'.$_ClassName.'.class'.IS_EXP) :
					return require IS_CORE.'Session/'.$_ClassName.'.class'.IS_EXP;
			break;
			
			//模板处理层
			case file_exists(IS_CORE.'View/'.$_ClassName.'.class'.IS_EXP) :
					return require IS_CORE.'View/'.$_ClassName.'.class'.IS_EXP;
			break;
			
			//加载项目控制器
			case file_exists(ACTION_PAHT . $_ClassName .  IS_EXP) :
					return require ACTION_PAHT . $_ClassName .  IS_EXP;
			break;
			
			//加载项目验证层
			case  file_exists(CHECK_PAHT . $_ClassName .  IS_EXP):
					return require CHECK_PAHT . $_ClassName .  IS_EXP;
			break;	
			
			//加载项目逻辑层
			case  file_exists(LOGIC_PAHT . $_ClassName .  IS_EXP):
					return require LOGIC_PAHT . $_ClassName .  IS_EXP;
			break;
			
			//加载项目模型层
			case  file_exists(MODEL_PAHT . $_ClassName .  IS_EXP):
					return require MODEL_PAHT . $_ClassName .  IS_EXP;
			break;
			
			//异常处理
			default:
				vd($_ClassName,1);
				exit('Abnormal data,Class does not exist');
			break;
		}			
	}

	/**
	 * 记录程序执行时间
	 */
	protected function runTime(){
		if(IS_RUNTIME){
			$endtime=microtime(true);
			$total=$endtime-RUNTIME;
			DataLog::write($total,'runTime.log');
		}
	}
	
	/**
	 * 自定义异常处理
	 * @access public
	 * @param mixed $e 异常对象
	 */
	static public function AppException($exception) {
		$error = array();
		$error['message']   = $exception->getMessage();
		$trace  =   $exception->getTrace();
		if('throw_exception'==$trace[0]['function']) {
			$error['file']  =   $trace[0]['file'];
			$error['line']  =   $trace[0]['line'];
		}else{
			$error['file']      = $exception->getFile();
			$error['line']      = $exception->getLine();
		}
		DataLog::write($error['message'], 'exception_php_error.log');
		get_debug($error);
	}
	
	
	/**
	 * 自定义错误处理
	 * @access public
	 * @param int $errno 错误类型
	 * @param string $errstr 错误信息
	 * @param string $errfile 错误文件
	 * @param int $errline 错误行数
	 * @return void
	 */
	static public function AppError($errno, $errstr, $errfile, $errline) {
		switch ($errno) {
			case E_ERROR:
			case E_PARSE:
			case E_CORE_ERROR:
			case E_COMPILE_ERROR:
			case E_USER_ERROR:
					ob_end_clean();
					$errorStr = "$errstr " . $errfile . " 第 $errline 行.";
					DataLog::write($errorStr, 'app_php_error.log');
					function_exists('get_debug') ? get_debug($errorStr) : exit('ERROR:' . $errorStr);
				break;
			case E_STRICT:
			case E_USER_WARNING:
			case E_USER_NOTICE:
			default:
				break;
		}
	}
	
	/**
	 * 致命错误捕获
	 */
	public static function FatalError() {
		$error = error_get_last();
		if ($error) {
			switch($error['type']){
				case E_ERROR:
				case E_PARSE:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
				case E_USER_ERROR:
					ob_end_clean();
					$errorStr = '程序终止错误类型:' . $error['type'] . PHP_EOL .
						'错误信息:' . $error['message'] . PHP_EOL .
						'报错文件:' . $error['file'] . PHP_EOL .
						'报错行数:' . $error['line'] . PHP_EOL;
					DataLog::write($errorStr, 'php_error.log');
					function_exists('get_debug') ? get_debug($errorStr) : exit('ERROR:' . $errorStr);
					break;
			}
		}
	}	
}
