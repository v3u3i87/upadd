<?php

namespace console\action;

class TestAction extends \Upadd\Frame\Action{

    public function main()
    {
        return 'hi, welcome to use Upadd';
    }

    public function xml()
    {
        $this->setResponseType('xml');
        return ['info'=>'abc','key'=>1,'to'=>11];
    }

    public function json()
    {
        return ['info'=>'abc','key'=>1,'to'=>11];
    }

}