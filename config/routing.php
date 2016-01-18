<?php

Routes::get('/', 'works\action\DemoAction@home');

Routes::get('/faq','works\action\FaqAction@main');

Routes::group(array('prefix' => '/user','filters'=>'info'),function() {

    Routes::get('/info','works\action\DemoAction@info');

});


Routes::group(array('prefix' => '/name'),function() {

    Routes::get('/info','works\action\DemoAction@name');

});
