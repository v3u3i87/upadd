<?php

Routes::get('/', 'demo\action\HomeAction@main');

Routes::group(array('prefix' => '/user'), function ()
{

    Routes::any('/info', 'demo\action\HomeAction@info');

});
