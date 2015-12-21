<?php

Routes::get('/', 'works\action\IndexAction@home');

Routes::get('/test',function(){
    $info = Session::get('info');
    vd($info);
});

Routes::group(array('prefix' => '/user','filters'=>'info'),function() {

    Routes::get('/info','works\action\IndexAction@info');

});


Routes::group(array('prefix' => '/name','filters'=>'test'),function() {

    Routes::get('/aaa','works\action\NameAction@aaa');

});
