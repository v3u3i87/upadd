<?php
namespace extend;

use Model;

/**
 * Class InfoModel
 * @package extend
 * test sql
CREATE TABLE `up_info` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(255) DEFAULT NULL COMMENT 'name',
`code` varchar(255) DEFAULT NULL COMMENT 'code',
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='信息'
 *
 */
class InfoModel extends Model
{

    //设置数据库名称
    protected $_table = 'info';

    protected $_primaryKey = 'id';


    public static function name()
    {
        return self::get();
    }




}