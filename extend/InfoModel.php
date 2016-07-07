<?php
namespace extend;
use Upadd\Frame\Model;

/**
 * Class InfoModel
 * @package extend
 * test sql
CREATE TABLE `up_info` (
`id` int(70) unsigned NOT NULL,
`name` char(50) NOT NULL,
`code` varchar(6) NOT NULL COMMENT '区号',
PRIMARY KEY (`id`),
KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
 *
 */
class InfoModel extends Model
{

    //设置数据库名称
    protected $_table = 'info';

    protected $_primaryKey = 'id';


    public function name()
    {

    }




}