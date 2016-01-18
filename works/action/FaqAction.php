<?php
namespace works\action;


class FaqAction extends BaseAction{

    public function main(){
        $this->val('name','Upadd使用常见问题');
        $this->view('main.html');
    }





}