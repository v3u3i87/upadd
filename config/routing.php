<?php

Routes::get('/', 'demo\action\HomeAction@main');

Routes::group(array('prefix' => '/user'), function () {

    Routes::get('/info', 'demo\action\DemoAction@info');

});
