<?php

$route->get('/', 'works\action\Info\ZmqAction@info');

$route->get('/test',function(){
    phpinfo();
});

$route->group(array('prefix' => '/user','filters'=>'info'),function() use ($route) {

    $route->get('/info','works\action\IndexAction@info');

});


$route->group(array('prefix' => '/name','filters'=>'test'),function() use ($route) {

    $route->get('/aaa','works\action\NameAction@aaa');

});
