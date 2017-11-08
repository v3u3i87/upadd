<?php
/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 16/4/25
 * Time: 19:36
 * Name:.
 */

namespace demo\action;

use Data;
use example\model\InfoModel as info;
use example\model\InfoAboutModel as about;
use example\model\InfoComModel as com;

class TestModelAction extends \Upadd\Frame\Action
{

    public function demo()
    {

        //开启事务
        info::begin();
        $info_id = info::add([
            'code'=>verificationCode(),
            'name'=>mt_rand(),
        ]);

        if ($info_id) {
            $is_about = about::add([
                'info_id' => $info_id,
                'uname' => mt_rand()
            ]);

            if ($is_about) {
                $is_com = com::add([
                    'info_id' => $info_id,
                    'vcode' => 33
                ]);
                if ($is_com) {
                    echo '成功';
                    echo "<br />";
                    info::commit();
                }else{
                    echo '$is_com失败';
                    info::rollback();
                }
            }else{
                echo '$is_about失败';
                info::rollback();
            }

        } else {
            echo '失败';
            info::rollback();
        }

    }


    private function begin_a()
    {
        $info = new info();
        //开启事务
        $info->begin();
        $info->code = verificationCode();
        $info->name = mt_rand();
        $info_id = $info->save();
        if ($info_id) {
            $is_about = about::add([
                'info_id' => $info_id,
                'uname' => mt_rand()
            ]);

            if ($is_about) {
                $is_com = com::add([
                    'info_id' => $info_id,
                    'vcode' => 33
                ]);
                if ($is_com) {
                    echo '成功';
                    echo "<br />";

                    $info->commit();
                }else{
                    echo '$is_com失败';
                    $info->rollback();
                }
            }else{
                echo '$is_about失败';
                $info->rollback();
            }

        } else {
            echo '失败';
            $info->rollback();
        }
    }

    private function _get()
    {
        $info = new info();
        //根据主键ID值 返回一条信息
//        $info->by(67);
        //返回指定字段的值
//        $info->where(['id'=>67])->one('code');
        /**
         * 单行查询
         */
//         $results = $info->where(['id'=>76])->find();

        /**
         * sort() 根据id排序，默认为倒序bool $by true/DESC,false/ASC
         * page() 默认分页10条一页,可传数值控制分页数
         * get() 返回列表
         */
//       $results = $info->sort('id',true)->page()->get();
        /**
         * 设置具体的分页数，不返回page类型
         */
//        $results = $info->limit(1,30)->get();
        /**
         * 可排序的指定分页方式
         */
//        $results = $info->sort('id')->limit(1,30)->get();
        //返回表总条数,可以联合where一起使用
//        $results = $info->count();

        //获取表所有字段
//        $results = $info->getTableField();
        //模糊查询,可联合where使用
        $results = $info->like('code', "1%")->get();
        return $results;
    }

    private function up()
    {
        $info = new info();
        $info->delete_time = time();
        $info->is_delete = 1;
        $in = $info->where(['id' => 67]);
        vd($in->save());

//        vd(InfoModel::where(['id'=>65])->save(['delete_time'=>time(),'is_delete'=>1]));
//        vd(InfoModel::where(['id'=>68])->update(['delete_time'=>time(),'is_delete'=>1]));
    }

    private function _add()
    {
//        $addData = [
//            'name'=>mt_rand(1111,3333),
//            'code'=>verificationCode(4),
//            'add_time'=>time(),
//        ];
//
//        $in = InfoModel::add($addData);

        $info = new info();
        $info->code = verificationCode();
        $info->name = mt_rand(1111, 3333);
        $getId = $info->save();
        if ($getId) {
            return $getId;
        }
        return false;
    }

    private function _save()
    {
        $info = new info();
        $info->name = mt_rand(1111, 3333);
        $info->code = verificationCode(4);
        $in = $info->save();
        if ($in) {
            return $in;
        }
        return false;
    }


    private function _del()
    {
        /**
         * 方法体里面传数组类型或是字符串的where条件
         */
        vd(info::del(['id' => 75]));
    }


}