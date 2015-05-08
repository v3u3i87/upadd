<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/4/14
 * Time: 16:43
 */
namespace App\Home\Model;

use Upadd\Frame\Model;


class Ad extends Model{


    public $_table = 'members_core';


    /**
     * 生成
     * @param string $eid
     */
    public function shengcheng($eid=''){
        if($eid){
            $eid = trim($eid);
            //查询是否存在
            $isEid = $this->_find('id'," eid='{$eid}' ");

            if(!$isEid){
                $data = array('eid'=>$eid,'is_state'=>1,'ctime'=>time());
                $this->add($data);
            }
        }
    }


    public function Links(){
        //返回所有的数据
       // $data =  $this->_show();
       // p($data);

        //根据字段查询返回
//        $data =  $this->_show('account_id,username,nikename'," account_id !=1 ");
//        return $data;

        //链式查询 单行
//        $data = $this->where(array('account_id'=>'22'))->find();
//        return $data;
//        $this->save(array('nikename'=>'啊嘎嘎')," account_id='69' ");
//
//        $this->add(
//            array(
//                'username'=>'abca',
//                'nikename'=>'抢在',
//                'password'=>'20c930add1025420d8f36bfc025bcb41',
//                'email'=>'zhangmaoqiang@renrenfenqi.com',
//                'create_time'=>0,
//                'status'=>1,
//                'flag_valid'=>1
//            ));
//        $this->del(" account_id='77' ");

        $data = $this->in_where('account_id','2,3,4,6',' not in')
            ->sort('account_id')
            ->select();
        return $data;
    }













}