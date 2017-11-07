<?php
namespace example\model;

use Upadd\Frame\Model;

class InfoModel extends Model{

    protected $_table = 'info';

    protected $_primaryKey = 'id';

    protected $_automaticityTime = true;

    //is_delete type tinyint = 1/正常,2/删除




}