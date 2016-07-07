<?php

namespace Upadd\Bin\Cache;

use Upadd\Bin\UpaddException;

use Config;

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
class Mem
{
	/**
	 * Memcache对象实
	 * @var Memcache
	 */
	private $_memcache = null;

	protected $config = [];
	
	/**
	 * @see Cache#_connect()
	 */
	public function __construct()
    {
		if ($this->_memcache !== null)
        {
			return $this->_memcache;
		}
		if (! extension_loaded ( 'memcache' ))
        {
            throw new UpaddException('Load memcache extension failure!');
		}
        $this->config = Config::get('tag@memcache');
        $this->connect();
	}

    protected function connect()
    {
        $this->_memcache = new \Memcache ();
        if (! $this->_memcache->connect ( $this->config ['host'], $this->config ['port'] ))
        {
            throw new UpaddException("Connect to memcache server failure({$this->config['host']}:{$this->config['port']})!");
        }
        return true;
    }
	
	/**
	 * @see Cache#has($key)
	 */
	public function has($key)
    {
		if ($this->_memcache)
        {
			return $this->_memcache->get ( $key ) !== false;
		} else {
			return false;
		}
	}
	
	/**
	 * 获取数据
	 * @see Cache #get($key)
	 */
	public function get($key)
    {
		if ($this->_memcache)
        {
			$retData = $this->_memcache->get ( $key );
			if ($retData !== false)
            {
				return unserialize ( $retData );
			}
		}
		return false;
	}
	
	/**
	 * 设置参数
	 * @see Cache#set($key, $val, array $extra = null)
	 */
	public function set($key, $val, array $extra = null)
    {
		if (! $this->_memcache)
        {
			return false;
		}
		
		if (isset ( $extra ['compression'] ))
        {
			$flag = $extra ['compression'] ? MEMCACHE_COMPRESSED : 0;
		} else {
			$flag = $this->config ['compression'] ? MEMCACHE_COMPRESSED : 0;
		}

		if ( isset ( $extra ['lifetime'] ))
        {
			$lifetime = $extra ['lifetime'];
		} else {
			$lifetime = $this->config ['lifetime'];
		}
		return $this->_memcache->set ( $key, serialize ( $val ), $flag, $lifetime );
	}
	
	/**
	 *
	 * @see Cache#delete($key)
	 */
	public function delete($key)
    {
		if ($this->_memcache)
        {
			return $this->_memcache->delete ( $key );
		} else {
			return false;
		}
	}
	
	/**
	 * 关闭
	 * @see Cache#_disConnect()
	 */
	protected function close()
    {
		if ($this->_memcache)
        {
			$this->_memcache->close ();
			$this->_memcache = null;
		}
	}
	
	/**
	 *
	 * @see Cache#getHandler()
	 */
	public function getHandler()
    {
		return $this->_memcache;
	}
}
