<?php

Routes::get('/', function(){
    return 'hi, welcome to use upadd in Anonymous functions...';
});


Routes::group(array('prefix' => '/test'), function ()
{

    Routes::any('/main', 'demo\action\HomeAction@test');
    //testPath
    Routes::any('/path', 'demo\action\HomeAction@testPath');

    Routes::get('/json', 'demo\action\HomeAction@json');

    Routes::get('/xml', 'demo\action\HomeAction@xml');

    //提交json数据
    Routes::post('/data/json', 'demo\action\TestDataAction@getJson');

    //提交数据流 stream
    Routes::post('/data/stream', 'demo\action\TestDataAction@stream');

    Routes::any('/model/add', 'demo\action\TestModelAction@add');


});
