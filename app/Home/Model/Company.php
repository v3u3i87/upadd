<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/4/15
 * Time: 18:22
 */

namespace App\Home\Model;

use Upadd\Frame\Model;

class Company extends Model{

    public $_table = 'company';
    
    public  function is_add($email,$data) {
        if($email && $data){
                $is = $this->where(" email='{$email}' ")->find('id');
                if(!$is){
                     $this->add(array('email'=>$email,'info'=>$data,'ctime'=>time())); 
                }
        }
    }



}