<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/7/14
 * Time: 16:39
 */

namespace works\action;


class NameAction extends \Upadd\Frame\Action{


    public function aaa(){
        
        if(isset($_GET['abc'])){
            echo $_GET['abc'];
        }
        //phpinfo();
        echo 6666666666;
    }


}