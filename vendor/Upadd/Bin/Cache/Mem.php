<?php

namespace Upadd\Bin\Cache\Mem;

use Upadd\Bin\Cache\Cache;

/**
 * Memcached缓存管理器
 *
 * $params = array // 构造参数形式
 * (
 *		'host'				=> 'localhost',
 *		'port'				=> 3306,
 *		'timeout'			=> 3,			// 连接超时时间
 *		'compression'		=> true,		// 默认是否压缩的标志
 *		'lifetime'			=> 3600,		// 默认缓存时间
 *		'enableDelayExpire'	=> false,		// 是否开启延迟删除失效数据操作
 *		'delayExpireTime'	=> null			// 延迟删除失效数据时间
 * );
 */
class Mem extends Cache {
	/**
	 * Memcache对象实例
	 *
	 * @var Memcache
	 */
	private $_memcache = null;
	protected $_params = array (
			'host' => 'localhost',
			'port' => 11211,
			'timeout' => 3, // 连接超时时间
			'compression' => true, // 默认是否压缩的标志
			'lifetime' => 3600  // 默认缓存时间
	);
	
	/**
	 *
	 * @see Cache#_connect()
	 */
	public function _connect() {
		if ($this->_memcache !== null) {
			return $this->_memcache;
		}
		if (! extension_loaded ( 'memcache' )) {
			$this->_memcache = false;
			$this->_setLastErrorInfo ( 'Load memcache extension failure!' );
			return false;
		}
		
		$this->_memcache = new Memcache ();
		if (! $this->_memcache->connect ( $this->_params ['host'], $this->_params ['port'] )) {
			$this->_memcache = false;
			$this->_setLastErrorInfo ( "Connect to memcache server failure({$this->_params['host']}:{$this->_params['port']})!" );
			return false;
		}
		return true;
	}
	
	/**
	 *
	 * @see Cache#has($key)
	 */
	public function has($key) {
		if ($this->_memcache) {
			return $this->_memcache->get ( $key ) !== false;
		} else {
			return false;
		}
	}
	
	/**
	 *
	 * @see Cache #get($key)
	 */
	public function get($key) {
		if ($this->_memcache) {
			$retData = $this->_memcache->get ( $key );
			if ($retData !== false) {
				return unserialize ( $retData );
			}
		}
		return false;
	}
	
	/**
	 *
	 * @see Cache#set($key, $val, array $extra = null)
	 */
	public function set($key, $val, array $extra = null) {
		if (! $this->_memcache) {
			return false;
		}
		
		if (! empty ( $extra ) && isset ( $extra ['compression'] )) {
			$flag = $extra ['compression'] ? MEMCACHE_COMPRESSED : 0;
		} else {
			$flag = $this->_params ['compression'] ? MEMCACHE_COMPRESSED : 0;
		}
		if (! empty ( $extra ) && isset ( $extra ['lifetime'] )) {
			$lifetime = $extra ['lifetime'];
		} else {
			$lifetime = $this->_params ['lifetime'];
		}
		return $this->_memcache->set ( $key, serialize ( $val ), $flag, $lifetime );
	}
	
	/**
	 *
	 * @see Cache#delete($key)
	 */
	public function delete($key) {
		if ($this->_memcache) {
			return $this->_memcache->delete ( $key );
		} else {
			return false;
		}
	}
	
	/**
	 *
	 * @see Cache#_disConnect()
	 */
	protected function _disConnect() {
		if ($this->_memcache) {
			$this->_memcache->close ();
			$this->_memcache = null;
		}
	}
	
	/**
	 *
	 * @see Cache#getHandler()
	 */
	public function getHandler() {
		return $this->_memcache;
	}
}
