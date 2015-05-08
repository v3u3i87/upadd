<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/4/14
 * Time: 16:43
 */
namespace App\Home\Model;

use Upadd\Frame\Model;


class Name extends Model{

    public $_table = 'student_account';


    public function info(){
        $data = $this->where(' uid=3 ')->find('user_name,nikename');
        return $data;
    }







}