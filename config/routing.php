<?php

Routes::get('/', function(){
    return 'hi, welcome to use upadd in Anonymous functions';
});


Routes::group(array('prefix' => '/test'), function ()
{

    Routes::any('/main', 'demo\action\HomeAction@test');
    //testPath
    Routes::any('/path', 'demo\action\HomeAction@testPath');


});
