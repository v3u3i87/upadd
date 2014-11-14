<?php
/**
 +----------------------------------------------------------------------
 | UPADD [ Can be better to Up add]
 +----------------------------------------------------------------------
 | Copyright (c) 20011-2014 http://upadd.cn All rights reserved.
 +----------------------------------------------------------------------
 | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
  +----------------------------------------------------------------------
 */
is_upToken();
/**
 * 全局http处理
 */
class Http{
	/**
	 * REQUEST_URI
	 * @var string
	 */
	protected $_requestUri;
	
	/**
	 * 请求的基准url 
	 * @var string
	 */
	protected $_baseUrl = null;

	/**
	 * 请求的基准目录
	 * @var string
	 */
	protected $_basePath = null;
	
	/**
	 * 用户参数表
	 * @var array
	 */
	protected $_params = array();
	
	/**
	 * 获取所有的$_get
	 * @var string
	 */
	protected $_setGet = array();

	protected static $in;
	
	/**
	 * 实例化http
	 * @return Http
	 */
	public static function getHttp(){
		if (!(self::$in instanceof self)) {
			self::$in = new self();
		}
		return self::$in;
	}
	
	final public function __clone(){}
	
	final protected function __construct(){}
	
	/**
	 * 从superglobales变量以public的方式公开获取接口
	 * 顺序 1. GET, 2. POST 3. COOKIE, 4. SERVER, 5. ENV
	 *    
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		switch (true) {
			case isset($this->_params[$key]):
				return $this->_params[$key];
			case isset($_GET[$key]):
				return $_GET[$key];
			case isset($_POST[$key]):
				return $_POST[$key];
			case isset($_COOKIE[$key]):
				return $_COOKIE[$key];
			case ($key == 'REQUEST_URI'):
				return $this->getRequestUri();
			case isset($_SERVER[$key]):
				return $_SERVER[$key];
			case isset($_ENV[$key]):
				return $_ENV[$key];
			default:
				return null;
		}
	}
	
	/**
	 * 和__get相同
	 * 
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		return $this->__get($key);
	}

	/**
	 * 对全局变量设置值是禁止的
	 * 
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key, $value)
	{
		throw new Exception('Setting values in superglobals not allowed; please use setParam()');
	}
	
	/**
	 * 和__set功能相同
	 * 
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key, $value)
	{
		return $this->__set($key, $value);
	}
	
	/**
	 * 判断是否存在了对应的属性
	 * 
	 * @param string $key
	 */
	public function __isset($key)
	{
		return isset($this->_params[$key])
			|| isset($_GET[$key])
			|| isset($_POST[$key])
			|| isset($_COOKIE[$key])
			|| isset($_SERVER[$key])
			|| isset($_ENV[$key]); 
	}

	/**
	 * 和__isset功能相同
	 * 
	 * @param string $key
	 */
	public function has($key)
	{
		return $this->__isset($key);
	}
	
	/**
	 * 从_GET获取参数值,如果$key为空，则返回__GET数组
	 * 
	 * @param string $key
	 * @param mixed $default
	 */
	public function getQuery($key = null, $default = null)
	{
		if (null === $key) {
			return $_GET;
		}
		
		return (isset($_GET[$key])) ? $_GET[$key] : $default;
	}
	
	
	/**
	 * 设置_GET的值
	 * 
	 * $param string|array $spec
	 * $param null|mixed $value
	 * @return Light_Request_Http
	 */
	public function setQuery($spec, $value = null)
	{
		if ((null === $value) && is_array($spec)) {		
			foreach($spec as $key => $value) {
				$this->setQuery($key, $value);				
			}
			return $this;
		}
		$_GET[(string)$spec] = $value;
		return $this;
	}
	
	/**
	 * 从_POST获取参数值.如果$key为空,则返回_POST数组
	 * 
	 * @param string $key
	 * @param mixed $default
	 */
	public function getPost($key = null, $default = null)
	{
		if (null === $key) {
			return $_POST;
		}
		
		return (isset($_POST[$key])) ? $_POST[$key] : $default;
	}
	
	
	/**
	 * 设置_POST的值
	 * 
	 * $param string|array $spec
	 * $param null|mixed $value
	 * @return Light_Request_Http
	 */
	public function setPost($spec, $value = null)
	{
		if ((null === $value) && is_array($spec)) {		
			foreach($spec as $key => $value) {
				$this->setPost($key, $value);				
			}
			return $this;
		}
		$_POST[(string)$spec] = $value;
		return $this;
	}
	
	/**
	 * 从_COOKIE获取参数值.如果$key为空,则返回_COOKIE数组
	 * 
	 * @param string $key
	 * @param mixed $default
	 */
	public function getCookie($key = null, $default = null)
	{
		if (null === $key) {
			return $_COOKIE;
		}
		
		return (isset($_COOKIE[$key])) ? $_COOKIE[$key] : $default;
	}
	
	
	/**
	 * 从_SERVER获取参数值.如果$key为空,则返回_SERVER数组
	 * 
	 * @param string $key
	 * @param mixed $default
	 */
	public function getServer($key = null, $default = null)
	{
		if (null === $key) {
			return $_SERVER;
		}
		
		return (isset($_SERVER[$key])) ? $_SERVER[$key] : $default;
	}
	
	/**
	 * 从_ENV获取参数值.如果$key为空,则返回_ENV数组
	 * 
	 * @param string $key
	 * @param mixed $default
	 */
	public function getEnv($key = null, $default = null)
	{
		if (null === $key) {
			return $_ENV;
		}
		
		return (isset($_ENV[$key])) ? $_ENV[$key] : $default;
	}
	
	/**
	 * 设置REQUEST_URI的值
	 * 
	 * @param string $requestUri
	 * @return Light_Request_Http
	 */
	public function setRequestUri($requestUri = null)
	{
		if (null === $requestUri) {
			if (isset($_SERVER['REDIRECT_URL'])) {
				$requestUri = $_SERVER['REDIRECT_URL'];
			} else if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
				$requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
			} else if (isset($_SERVER['REQUEST_URI'])) {
				$requestUri = $_SERVER['REQUEST_URI'];
				if (isset($_SERVER['HTTP_POST']) && strstr($requestUri, $_SERVER['HTTP_HOST'])) {
					$requestUri = preg_replace('#^[^:]*://[^/]*/#', '/', $requestUri);					
				}
			} else {
				return $this;
			}
		} else if (!is_string($requestUri)) {
			return $this;
		} else {
			if (false !== ($pos = strpos($requestUri, '?'))) {
				$query = substr($requestUri, $pos + 1);
				parse_str($query, $vars);
				$this->setQuery($vars);
			}
		}
	
		$this->_requestUri = $requestUri;
		return $this;
	}
	
	/**
	 * 获取REQUEST_URI
	 * 
	 * @return string
	 */
	public function getRequestUri()
	{
		if (empty($this->_requestUri)) {
			$this->setRequestUri();
		}		
		return $this->_requestUri;
	}
		
	/**
	 * 设置baseUrl
	 * 如果是null，那么从当前环境变量生成
	 * 
	 * @param string|null $baseUrl
	 */
	public function setBaseUrl($baseUrl = null)
	{
		if ((null !== $baseUrl) && !is_string($baseUrl)) {		
			return $this;
		}
		
		if (null === $baseUrl) {
			$filename = (isset($_SERVER['SCRIPT_NAME'])) ? basename($_SERVER['SCRIPT_FILENAME']) : '';
			
			if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === $filename) {
				$baseUrl = $_SERVER['SCRIPT_NAME'];
			} else if (isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) === $filename) {
				$baseUrl = $_SERVER['PHP_SELF'];
			} else {
				$path    = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
				$file    = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
				$segs    = explode('/', trim($file, '/'));
				$index   = 0;
				$last    = count($segs);
				$baseUrl = '';
				do {
					$seg     = $segs[$index];
					$baseUrl = '/' . $seg . $baseUrl;
					++$index;
				} while(($last > $index) && (false !== ($pos = strpos($path, $baseUrl))) && (0 != $pos));
			}
		
			$requestUri = $this->getRequestUri();
			
			if (0 === strpos($requestUri, $baseUrl)) {
				$this->_baseUrl = $baseUrl;
				return $this;
			}
			
			if (0 === strpos($requestUri, dirname($baseUrl))) {
				$this->_baseUrl = rtrim(dirname($baseUrl), '/');
				return $this;			
			}
			
			if (!strpos($requestUri, basename($baseUrl))) {
				$this->_baseUrl = '';
				return $this;
			}
			
			if ((strlen($requestUri) >= strlen($baseUrl))
				&& ((false !== ($pos = strpos($requestUri, $baseUrl))) && ($pos !== 0))) {
				$baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));					
			}
		}
		
		$this->_baseUrl = rtrim($baseUrl, '/');
		return $this;
	}
	
	/**
	 * 获取BaseUrl
	 *
	 * @return string
	 */
	public function getBaseUrl()
	{
		if (null === $this->_baseUrl) {
			$this->setBaseUrl();		
		}
		
		return $this->_baseUrl;
	}
	
	/**
	 * 设置url的基础路径
	 * 
	 * @param string|null $basePath
	 * @return Light_Request_Http
	 */
	public function setBasePath($basePath = null)
	{
		if (null === $basePath) {
			$filename = basename($_SERVER['SCRIPT_FILENAME']);
			
			$baseUrl = $this->getBaseUrl();
			if (empty($baseUrl)) {
				$this->_basePath = '';
				return $this;
			}
			
			if (basename($baseUrl) === $filename) {
				$basePath = dirname($baseUrl);		
			} else {
				$basePath = $baseUrl;
			}
		}
		
		if (substr(PHP_OS, 0, 3) === 'WIN') {
			$basePath = str_replace('\\', '/', $basePath);
		}
		
		$this->_basePath = rtrim($basePath, '/');
		return $this;
	}
	
	/**
	 * 获取基础路径
	 * 
	 * @return string
	 */
	public function getBasePath()
	{
		if (null === $this->_basePath) {
			$this->setBasePath();
		}		
		
		return $this->_basePath;
	}
	
	/**
	 * 获取参数
	 * @param mixed $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getParam($key=null, $default = null){
		if(is_string($key)){
			if(isset($_GET[$key])){
				return $_GET[$key];
			}elseif (isset($_POST[$key])){
				return $_POST[$key];
			}
		}elseif (empty($key)){
			return self::__TypeGetPost($_POST);
		}
		return $default;							
	}
	
	/**
	 * 处理过滤
	 * @param string $_fields
	 * @return multitype:unknown |boolean
	 */
	protected function __TypeGetPost($_fields=null){
		$_Data = array();
		if (Verify::IsArr($_fields) && !Verify::isNullArray($_fields)) {
			foreach ($_fields as $_key=>$_value) {
				$_Data[$_key] = $_value;
			}
			return $_Data;
		}
	}

	
	/**
	 * 获取对端地址
	 * 
	 * @return string
	 */
	public function getRemoteAddr()
	{
		$remoteAddr = $this->getServer('HTTP_X_FORWARDED_FOR');
		if (empty($remoteAddr)) {	
			$remoteAddr = $this->getServer('REMOTE_ADDR');
		}
		return $remoteAddr;
	}
	
	/**
	 * 获取请求的方式
	 * 
	 * @reutrn string
	 */
	public function getMethod()
	{
		return $this->getServer['REQUEST_METHOD'];
	}
	
	/**
	 * 判断请求时是否是POST方式
	 * 
	 * @return boolean
	 */
	public function isPost()
	{
		return  ('POST' == $this->getMethod());
	}
	
	/**
	 * 判断请求是否是GET方式
	 * 
	 * @return boolean
	 */
	public function isGet()
	{
		return ('GET' == $this->getMethod());	
	}
	
	/**
	 * 判断请求时否是XMLHttpRequest方式
	 * 
	 * @return boolean
	 */
	public function isXmlHttpRequest()
	{
		return ($this->getHeader('X_REQUEST_WITH') == 'XMLHttpRequest');
	}
	
	/**
	 * 判断是否是Flash发送的请求
	 * 
	 * @return boolean
	 */
	public function isFlashRequest()
	{
		$header = strtolower($this->getHeader('USER_AGENT'));
		return (strstr($header, 'flash')) ? true : false;		
	}
	
	/**
	 * 获取Post数据流
	 * 
	 * @return string|false
	 */
	public function getRawBody()
	{
		$body = file_get_contents('php://input');
		
		if (strlen(trim($body)) > 0) {
			return $body;
		}
		return false;
	}
	
	/**
	 * 根据Http头Key获取值
	 * 
	 * @param string $header HTTP头名称
	 * @return string|false
	 */
	public function getHeader($header)
	{
		if (empty($header)) {
			return false;
		}

		$temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
		if (!empty($_SERVER[$temp])) {
			return $_SERVER[$temp];
		}

		return false;
	}
}
