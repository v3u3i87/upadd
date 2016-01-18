<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/7/4
 * Time: 13:19
 */

namespace Upadd\Bin\Tool;

use Upadd\Bin\Http\Route;

class Rbac extends Route{


   public static function checkUser()
   {

       $action = static::$action;
       $method = static::$method;
       p(array($action,$method));


        $user_module = m('user_module');
        $model = $user_module->join(array('user_module'=>'a','user_operate'=>'b'))
           ->where(" a.id=b.module_id AND a.mode=b.mode AND a.action_name='{$action}' AND b.operate_name='{$method}' ")->find('b.id');
        if($_SESSION['user'] && $model){
           $user = $_SESSION['user'];
           if(in_array($model['id'],$user['roles_data'])){
                return true;
           }else{
               return false;
           }
        }
   }





}