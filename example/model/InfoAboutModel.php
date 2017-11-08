<?php
namespace example\model;

use Upadd\Frame\Model;

/**
 * Class InfoAboutModel
 * @package example\model
CREATE TABLE `up_info_about` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增',
    `info_id` int(10) NOT NULL COMMENT '信息ID',
    `uname` varchar(30) NOT NULL COMMENT '姓名',
    `add_time` int(10) NOT NULL COMMENT '添加时间',
    `update_time` int(10) DEFAULT NULL,
    `delete_time` int(10) DEFAULT NULL,
    `is_delete` tinyint(1) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='关于';
 */
class InfoAboutModel extends Model{

    protected $_table = 'info_about';

    protected $_primaryKey = 'id';

    protected $_automaticityTime = true;

    //is_delete type tinyint = 1/正常,2/删除




}