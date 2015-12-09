<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/7/4
 * Time: 13:19
 */

namespace Upadd\Bin\Tool;

class Rbac
{

    public static $_action = array();

    public static $_name = null;

    public static function setMode($action){
       if($action){
           return self::checkUser($action);
       }
    }

   public static function checkUser($action){
        $user_module = m('user_module');
        $model = $user_module->join(array('user_module'=>'a','user_operate'=>'b'))
           ->where(" a.id=b.module_id AND a.mode=b.mode AND a.action_name='{$action[0]}' AND b.operate_name='{$action[1]}' ")->find('b.id');
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