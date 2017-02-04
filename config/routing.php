<?php

Routes::get('/', function(){
    return 'hi..';
});


Routes::group(array('prefix' => '/user'), function ()
{

    Routes::any('/main', 'demo\action\HomeAction@main');

});
