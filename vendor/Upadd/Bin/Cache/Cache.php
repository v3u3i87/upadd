<?php

namespace Upadd\Bin\Cache;

/**
 * 缓存查询虚基类
 */
abstract class Cache {
	
	/**
	 * 最后一次操作失败的错误代码
	 *
	 * @var Integer
	 */
	protected $_lastErrorCode = null;
	
	/**
	 * 最后一次操作失败的错误原因
	 *
	 * @var String
	 */
	protected $_lastErrorMessage = null;
	
	/**
	 * 服务器连接参数
	 *
	 * @var array
	 */
	protected $_params = array ();
	
	/**
	 * 构造函数
	 */
	public function __construct(array &$params) {
		foreach ( $params as $key => $val ) {
			if (isset ( $this->_params [$key] )) {
				$this->_params [$key] = $val;
			}
		}
	}
	
	/**
	 * 初始化缓存服务器的连接操作
	 *
	 * @return boolean
	 */
	abstract protected function _connect();
	
	/**
	 * 判断是否存在key所对应的值
	 *
	 * @param mixed $key        	
	 * @return boolean
	 */
	abstract public function has($key);
	
	/**
	 * 得到对应key所表示的值
	 *
	 * @param mixed $key        	
	 * @return mixed
	 */
	abstract public function get($key);
	
	/**
	 * 设置对应key所代表的值
	 *
	 * @param String $key        	
	 * @param String $value        	
	 * @return boolean
	 */
	abstract public function set($key, $value, array $extra = null);
	
	/**
	 * 删除对应key所代表的值
	 * @param $key
	 * @return boolean
	 */
	abstract public function delete($key);
	
	/**
	 * 获取原生的缓存连接资源
	 *
	 * @return resource|Object(虚)
	 */
	abstract public function getHandler();
	
	/**
	 * 断开与缓存服务器的连接操作
	 *
	 * @return void
	 */
	abstract protected function _disConnect();
	
	/**
	 * 释放连接资源
	 */
	public function __destruct() {
		$this->_disConnect ();
	}
	
	/**
	 * 返回最后一次操作失败的错误代码
	 *
	 * @return Integer
	 */
	public function getLastErrorCode() {
		return $this->_lastErrorCode;
	}
	
	/**
	 * 返回最后一次操作失败的错误原因
	 *
	 * @return String
	 */
	public function getLastErrorMessage() {
		return $this->_lastErrorMessage;
	}
	
	/**
	 * 设置最后一次操作失败的错误信息
	 *
	 * @return void
	 */
	protected function _setLastErrorInfo($errorMessage = '', $errorCode = -1) {
		$this->_lastErrorCode = $errorCode;
		$this->_lastErrorMessage = $errorMessage;
	}
}