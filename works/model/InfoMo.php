<?php

namespace works\model;

class InfoMo extends \Upadd\Frame\Model{

    public $_table = 'ad';

    public function info(){
        return $this->select();
    }




}