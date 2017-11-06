<?php
namespace Upadd\Bin;

class Tag{

    private $instance = [];

    /**
     * 实例化
     * @param array $tag
     */
    public function init($init)
    {
        $this->instance = $init;
    }

    /**
     * 添加标签
     * @param null $key
     */
    public function add($key=null)
    {
        if($key){
            $this->instance = array_merge($this->instance,$key);
        }
    }

    /**
     * 获取标签
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        if(isset($this->instance[$key])){
            return $this->instance[$key];
        }
        return null;
    }

    /**
     * 删除标签
     */
    public function del($key)
    {
        if(isset($this->instance[$key]))
        {
            unset($this->instance[$key]);
            return true;
        }
        return false;
    }

    public function all()
    {
        return $this->instance;
    }


    public function status(){}




}