<?php

Routes::get('/', function(){
    return 'hi..';
});


Routes::group(array('prefix' => '/test'), function ()
{

    Routes::any('/main', 'demo\action\HomeAction@test');

    Routes::any('/file', 'demo\action\HomeAction@getFile');

});
