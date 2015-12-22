<?php

namespace works\model;

class Demo extends BaseModel{

    //设置数据库名称
    public $_table = 'demo';

    public function abc(){
        return $this->select();
    }






}