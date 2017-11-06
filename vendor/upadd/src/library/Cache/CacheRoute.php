<?php

namespace Upadd\Bin\Cache;

use Upadd\Bin\Config\Configuration;
use Upadd\Bin\UpaddException;

/**
 * 缓存路由列表
 * Class CacheRoute
 * @package Upadd\Bin\Cache
 */
class CacheRoute
{

    /**
     * 系统缓存目录
     * @var string
     */
    public $system_dir = '';

    /**
     * 缓存文件
     * @var string
     */
    public $file = '';

    /**
     * 路由配置文件
     * @var string
     */
    public $routeFile = '';


    /**
     * 路由列表
     * @var array
     */
    private $routeList = [];


    /**
     * 初始化
     * CacheRoute constructor.
     */
    public function __construct()
    {
        $this->is_dir();
        $this->routeFile = host().'config/routing.php';
        $this->file = $this->system_dir.'route.cache';
    }

    /**
     * 获取
     * @param array $routeList
     */
    public function setRouteList($routeList=[])
    {
        if($routeList)
        {
            $this->routeList = $routeList;
        }
    }

    /**
     * 返回路由列表
     * @return array
     */
    public function getRouteList()
    {
        return $this->routeList;
    }


    /**
     * 判断是否更新缓存文件
     * @return bool
     * @throws UpaddException
     */
    public function is_up_file()
    {
        if (! file_exists ( $this->file ) || filemtime ( $this->file ) < filemtime ( $this->routeFile ))
        {
            $data = sequence($this->routeFile,true);
            if (! file_put_contents ( $this->file, $data))
            {
                throw new UpaddException('路由缓存失败' . $this->file,404);
            }
            return false;
        }else{
            return true;
        }
    }

    /**
     * 判断文件是否存在
     * @return bool
     */
    public function is_file()
    {
        if(file_exists($this->file))
        {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 判断系统目录是否存在
     */
    public function is_dir()
    {
        $this->system_dir = Configuration::get('sys@system_dir');
        is_create_dir($this->system_dir);
    }




}