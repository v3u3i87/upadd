<?php

use Upadd\Bin\Route;

Route::get('/', 'works\action\IndexAction@home');

Route::group(array('prefix' => '/user','filters'=>'is_login'),function(){

    Route::get('/home/abc', 'works\action\IndexAction@abc');

});


Route::group(array('prefix' => '/in','filters'=>'check'),function(){

    Route::get('/main/aaa', 'works\action\IndexAction@home');

});


Route::dispatch();