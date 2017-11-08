<?php
namespace example\model;

use Upadd\Frame\Model;

/**
 * Class InfoComModel
 * @package example\model
CREATE TABLE `up_info_com` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增',
    `info_id` int(10) NOT NULL COMMENT '信息ID',
    `vcode` varchar(3) DEFAULT '0' COMMENT 'vcode',
    `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
    `update_time` int(10) DEFAULT NULL,
    `delete_time` int(10) DEFAULT NULL,
    `is_delete` tinyint(1) DEFAULT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COMMENT='社区';
 *
 */
class InfoComModel extends Model{

    protected $_table = 'info_com';

    protected $_primaryKey = 'id';

    protected $_automaticityTime = true;

    //is_delete type tinyint = 1/正常,2/删除




}