<?php
/**
 * Created by PhpStorm.
 * User: zmq
* Date: 2015/4/10
* Time: 13:43
*/

use Upadd\Frame\Action;


class Job extends Action{

    public $_com;
    
    public function __construct(){
         
    }
    
    
    public function index(){
           $acc = m('account');
           p($_SERVER['REQUEST_METHOD']);
    }

   




}