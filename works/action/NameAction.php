<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/7/14
 * Time: 16:39
 */

namespace works\action;

use Log;

class NameAction extends \Upadd\Frame\Action{


    public function aaa($info=array(),$name=''){

        for($i=0;$i <= 100000000000;$i++){
            $id = 'id:'.$i;
            Log::notes(array($id,$info,$name),'cli.log');
            echo $id;
            echo "\n";
        }

    }


}