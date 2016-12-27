<?php

Routes::get('/', function(){
    echo 'hi..';
});

Routes::group(array('prefix' => '/user'), function ()
{

    Routes::any('/info', 'demo\action\HomeAction@info');

});
