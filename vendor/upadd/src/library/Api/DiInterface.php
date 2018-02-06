<?php
namespace Upadd\Bin\Api;

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 2018/1/26
 * Time: 下午5:20
 * Name:
 */

interface DiInterface{


    /**
     * @param $name
     * @return mixed
     */
    public function binding($name);

    /**
     * @param $name
     * @return mixed
     */
    public function getBinding($name);

}