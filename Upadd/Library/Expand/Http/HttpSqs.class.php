<?php
defined('UPADD_HOST') or exit();
/**
 * 
 * @author 
 *
 */
class HttpSqs{
	//类单例静态变量
	private static $_instance	= null;
	
	/**
	 * 服务器的url连接参数
	 * @var string
	 */
	protected $_serverUrl		= '';
	
	/**
	 * 请求的key值
	 * @var string
	 */
	protected $_key				= '';
	
	/**
	 * 请求的url地址
	 * @var string
	 */
	protected $_url				= '';
	
	/**
	 * 字符集
	 * @var string
	 */
	protected $_charset			= '';
	
	/**
	 * 是否重置请求
	 * @var bool
	 */
	protected $_change			= false;
	
	/**
	 * 请求方式
	 * @var string
	 */
	protected $_opt				= '';
	
	/**
	 * curl连接句柄
	 * @var 
	 */
	protected $_ch				= null;
	
	/**
	 * 超时时间
	 * @var int
	 */
	protected $_timeOut			= 2;
	
	/**
	 * 构造器
	 * @param string $host
	 * @param string $port
	 */
	public function __construct($host = '', $port = '')
	{
		if (!empty($host) && !empty($port)) {
			$this->connect($host, $port);
		}
	}

	/**
	 * 禁止克隆对象
	 */
	private function __clone()
	{

	}
	
	/**
	 * 单例实现
	 * @param string $host
	 * @param int $port
	 * @param bool $change
	 * @return Public_HttpSqs
	 */
	public static function getInstance($host = '', $port = 1218, $change = false)
	{
		if (null == self::$_instance || true == $change) {
			self::$_instance = new self($host, $port);
		}

		return self::$_instance;
	}
	
	/**
	 * 设置连接参数
	 * @param string $host
	 * @param string $port
	 * @return void
	 */
	public function connect($host, $port)
	{
		if (!empty($host) && !empty($port)) {
			$this->_serverUrl = $host . ':' . $port;
		}
	}
	
	/**
	 * 取得连接的url地址
	 * @param 请求的key值 $key
	 * @return void
	 */
	protected function _setUrl($key, $opt, $charset)
	{
		if ($key && $this->_serverUrl) {
			$this->_key	= $key;
			if (empty($charset)) {
				$this->_url	=  $this->_serverUrl . '/?name=' . $key . '&opt=' . $opt;
			} else {
				$this->_url	=  $this->_serverUrl . '/?name=' . $key . '&charset=' . $charset . '&opt=' . $opt;
			}
		}
	}
	
	/**
	 * 取得key-value的value值
	 * @param 查询主键 $key
	 * @return mixed
	 */
	public function get($key, $charset = '')
	{
		if (empty($this->_url) || $key != $this->_key || $this->_opt != 'get') {
			$this->_setUrl($key, 'get', $charset);
			$this->_opt = 'get';
			
			if (empty($this->_url)) {
				return false;
			}

			$this->_change	= true;
		} else {
			$this->_change	= false;
		}

//		$request 	= new Public_HttpSqs_Request();
//		
//		$requester	= $this->getRequest();
//
//		$result		= $requester->setServer($this->_url)
//								->setKeepAlive(true)
//								->setChange($this->_change)
//								->sendRequest($request);
//		
//		if ($result->isValid()) {
//			return $result->getDataSet();
//		} else {
//			return '';
//		}

		$result	= $this->_sendRequest();
		if (isset($result->valid) && isset($result->data)) {
			return $result->data;
		} else {
			return '';
		}
	}
	
	/**
	 * 设置数据进队列
	 * @param string $key
	 * @param string $charset
	 * @return bool
	 */
	public function put($key, $charset, $data)
	{
		if (empty($this->_url) || $key != $this->_key || $charset != $this->_charset || $this->_opt != 'put') {
			$this->_setUrl($key, 'put', $charset);
			$this->_opt = 'put';
			
			if (empty($this->_url)) {
				return false;
			}

			$this->_change	= true;
		} else {
			$this->_change	= false;
		}

//		$request 	= new Public_HttpSqs_Request();
//		$request->addParam('data', $data);
//		
//		$requester	= $this->getRequest();
//		
//		$result		= $requester->setServer($this->_url)
//								->setKeepAlive(true)
//								->setChange($this->_change)
//								->addHeader('Expect:')
//								->setOpt('POST')
//								->setPostType(2)
//								->sendRequest($request);
//		
//		return $result->isValid() ? true : false;

		$result	= $this->_sendRequest('post', $data);
		
		return (isset($result->valid) && $result->valid == 1) ? true : false;
	}
	
	/**
	 * 设置curl
	 * @return $this->_ch
	 */
	private function _getCurlHandle()
	{
		if ($this->_ch == null || $this->_change) {
			$this->_ch = curl_init($this->_url);
			
			curl_setopt($this->_ch, CURLOPT_HTTPHEADER, array('Connection: Keep-Alive', 'Expect:'));
			curl_setopt($this->_ch, CURLOPT_TIMEOUT, $this->_timeOut);
			curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);	
		}
		return $this->_ch;
	}
	
	/**
	 * 发送请求
	 * @return stdClass
	 */
	private function _sendRequest($opt = 'get', $data = '')
	{
		$this->_getCurlHandle($opt);
		
		if ($opt == 'post') {
			curl_setopt($this->_ch, CURLOPT_POST, TRUE);
			curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $data);
		}
		
		$return = curl_exec($this->_ch);
		
		$result	= new stdClass();
		if (!$return) {
			$err = curl_errno($this->_ch);
			
			if ($err != 0) {
				$result->error	= 'curl err' . curl_error($this->_ch);
			}
		} else {
			/**
			 * 处理返回的数据
			 */
			$this->_parseResult($return, $result);
		}
		
		return $result;
	}
	
	/**
	 * 处理请求回来的数据
	 * @param string $data
	 * @return stdClass
	 */
	private function _parseResult($data, $result)
	{
		if (empty($data)) {
			$result->error	= 'data empty';
		}
		
		if ($data == false 
			|| $data["data"] == "HTTPSQS_ERROR" 
			|| $data["data"] == false 
			|| $data == 'HTTPSQS_ERROR') {
			$result->error	= '请求数据失败';
		} elseif ($data['data'] == 'HTTPSQS_PUT_END' || $data == 'HTTPSQS_PUT_END') {
			$result->error	= '队列已满';
		} elseif ($data == 'HTTPSQS_GET_END' || $data['data'] == 'HTTPSQS_GET_END') {
			$result->error	= '队列中已无数据';
		} elseif ($data['data'] == 'HTTPSQS_PUT_OK' || $data == 'HTTPSQS_PUT_OK') {
			$result->valid	= 1;
		} else {
			$result->valid	= 1;
			$result->data	= $data;
		}
		
		return $result;
	}	
}

/****
	public function demo(){
		LoadApi('HttpSqs.class.php');
		$data = array('适当放松的', 'awrwre', 'weqwe');
		
		//$data = serialize($data);
		$sqs = HttpSqs::getInstance(
				'112.124.65.38',
				'1218',
				true
		);
// 		for ($i=0;$i<100000;$i++){
// 			//$result = $sqs->put('test', 'utf8', 'i:'.$i.'我是强哥'.mt_rand(888, 99999));
// 			$result = $sqs->get('test', 'utf8');
// 			echo $result;
// 			echo '<br />';
// 		}
// 		exit;
		//$result = $sqs->get('test', 'utf8');
		//print_r($result);
		
		
// 		if ($result == false) {
// 			return false;
// 		}
		//return $result;
	}
 */


