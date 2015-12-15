<?php
namespace Upadd\Bin;

use \Upadd\Bin\UpaddException;

class Alias{

    public $_aliasData = array();

    public function __construct($all){
        $this->_aliasData = $all;
    }


    public function run(){
        try{

            foreach ($this->_aliasData as $alias => $name) {
                class_alias($name,$alias);
            }

        }catch(UpaddException $e){
            $e->getMessage();
        }
    }




}