<?php

namespace Upadd\Bin\Cache;

use Config;
use Upadd\Bin\UpaddException;

class getRedis
{

    /**
     * 对象
     * @var
     */
    private $redis;


    public function __construct($config = array())
    {
        if (!extension_loaded('Redis')) {
            throw new UpaddException('extension redis load failure!');
        }

        if ($this->redis !== null) {
            return $this->redis;
        }

        $this->connect();
    }


    /**
     * 链接
     */
    protected function connect()
    {
        $config = Config::get('cache@redis');
        $this->redis = new \Redis();
        $this->redis->connect($config['host'], $config['port']);
        if ($config['auth']) {
            $this->redis->auth($config['auth']);
        }
    }

    /**
     * 设置值
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param int $timeOut 时间
     */
    public function set($key, $value, $timeOut = 0)
    {
        $retRes = $this->redis->set($key, $value);
        if ($timeOut > 0) {
            $this->redis->setTimeout($key, $timeOut);
        }
        return $retRes;
    }

    /**
     * 通过KEY获取数据
     * @param string $key KEY名称
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * 删除一条数据
     * @param string $key KEY名称
     */
    public function delete($key)
    {
        return $this->redis->delete($key);
    }

    /**
     * 清空数据
     */
    public function flushAll()
    {
        return $this->redis->flushAll();
    }

    /**
     * 数据入队列
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param bool $right 是否从右边开始入
     */
    public function push($key, $value, $right = true)
    {
        $value = json_encode($value);
        return $right ? $this->redis->rPush($key, $value) : $this->redis->lPush($key, $value);
    }

    /**
     * 数据出队列
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     */
    public function pop($key, $left = true)
    {
        $val = $left ? $this->redis->lPop($key) : $this->redis->rPop($key);
        return json_decode($val);
    }

    /**
     * 数据自增
     * @param string $key KEY名称
     */
    public function increment($key)
    {
        return $this->redis->incr($key);
    }

    /**
     * 数据自减
     * @param string $key KEY名称
     */
    public function decrement($key)
    {
        return $this->redis->decr($key);
    }

    /**
     * key是否存在，存在返回ture
     * @param string $key KEY名称
     */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }


    /**
     * 判断剩余时间
     * @param $key
     * @return bool
     */
    public function ttl($key)
    {
        $ttl = $this->redis->ttl($key);
        if ($ttl > 1) {
            return $ttl;
        }
        return false;
    }


    /**
     * 更新时间
     * @param $key
     * @param $time
     * @return bool
     */
    public function expire($key, $time)
    {
        if ($this->exists($key))
        {
            if ($this->redis->expire($key, $time))
            {
                return true;
            }
        }
        return false;
    }


    /**
     * 返回redis对象
     * redis有非常多的操作方法，我们只封装了一部分
     * 拿着这个对象就可以直接调用redis自身方法
     */
    public function redis()
    {
        return $this->redis;
    }

}