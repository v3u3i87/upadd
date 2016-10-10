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
use Cache;
use Async;
use extend\InfoModel;

class HomeAction extends \Upadd\Frame\Action
{

    public $redis = null;

    public $memcache = null;

    public function main()
    {
        p(InfoModel::get());
    }

    protected function asyncGet()
    {
        $http = Async::http();
        //开启监听,并返回服务端数据
        $http->monitor();
        if($http->get('http://up.int.com/user/info'))
        {
            p($http->data(),1);
        }
    }

    protected function asyncPost()
    {
        $http = Async::Http();
        //开启监听,并返回服务端数据
        $http->monitor();
        if($http->post('http://up.int.com/user/info',['han'=>111,'name'=>'韩斌']))
        {
            p($http->data());
        }else{
            p($http->error());
        }
    }




    public function test()
    {
        echo 'hi, welcome to use Upadd';
    }


    public function testMemcache()
    {
        $memcache = Cache::memcache();
//        $memcache->set('regtion',[1,3,4,5,6],['lifetime'=>60]);
        return $memcache->get('regtion');
    }

    public function testResid()
    {
        $redis = Cache::redis();
        $redis->set('up',json(['info'=>1111,'name'=>'xiaohb']));
//        $this->redis->flushAll();
//        $this->redis->set('up',json(['info'=>1111,'name'=>'xiaohb']));
        return $redis->get('up');
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
