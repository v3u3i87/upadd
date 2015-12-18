<?php

namespace works\model;

class InfoMo extends BaseModel{

    //设置数据库名称
    public $_table = 'ad';

    public function abc(){
        return $this->select();
    }






}