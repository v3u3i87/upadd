<?php
/**
 * Created by PhpStorm.
 * User: zmq
 * Date: 2015/7/14
 * Time: 16:39
 */

namespace works\action;

use works\logic\Info;
use works\action\NameAction;

class IndexAction extends BaseAction{


    public function home(){
        $_msg = m('msg');
        $all = $_msg::get()->all();
        p($all);
    }

    public function abc(){
        p($_GET,1);
        $nid = param('nid','');
        $asdads = param('asdads','');
        echo $asdads;
        echo '<br />';
        echo $nid;
        echo '<br />';
        Info::in();
        $name = new NameAction();
        $name->aaa();
    }




}