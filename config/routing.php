<?php

Routes::get('/', function () {
    return 'welcome to use upadd...';
});


Routes::group(array('prefix' => '/test'), function () {

    Routes::any('/main', 'demo\action\TestDataAction@test');
    //testPath
    Routes::any('/path', 'demo\action\HomeAction@testPath');

    Routes::get('/json', 'demo\action\HomeAction@json');

    Routes::get('/xml', 'demo\action\HomeAction@xml');

    //提交json数据
    Routes::post('/data/json', 'demo\action\TestDataAction@getJson');

    //提交数据流 stream
    Routes::post('/data/stream', 'demo\action\TestDataAction@stream');

    Routes::any('/model/demo', 'demo\action\TestModelAction@demo');

});
