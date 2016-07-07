<?php
/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 16/4/25
 * Time: 19:36
 * Name:.
 */

namespace demo\action;

use Data;
use extend\getRedis;
use Upadd\Bin\Cache\Mem;
use extend\InfoModel;

class HomeAction extends \Upadd\Frame\Action
{

    public $redis = null;

    public $memcache = null;

    public function main()
    {
        return InfoModel::get();
    }

    public function test()
    {
        echo 'hi, welcome to use Upadd';
    }


    public function testMemcache()
    {
        $this->memcache = new Mem();
//        $this->memcache->set('regtion',[1,3,4,5,6],['lifetime'=>60]);
        return $this->memcache->get('regtion');
    }

    public function testResid()
    {
        $this->redis = new getRedis();
//        $this->redis->flushAll();
//        $this->redis->set('up',json(['info'=>1111,'name'=>'xiaohb']));
        return $this->redis->get('up');
    }

    public function info()
    {
        $all = Data::all();
        return $all;
    }


    /**
     * 获取文件加载信息
     * @param bool  $detail 是否显示详细
     * @return integer|array
     */
    public  function getFile($detail = false)
    {
        if ($detail) {
            $files = get_included_files();
            $info  = [];
            foreach ($files as $key => $file) {
                $info[] = $file . ' ( ' . number_format(filesize($file) / 1024, 2) . ' KB )';
            }
            return $info;
        }
        return count(get_included_files());
    }



}
