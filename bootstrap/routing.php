<?php
/**
 * Created by PhpStorm.
 * User: v3u3
 * Date: 2015/4/7
 * Time: 15:19
 */

use Upadd\Bin\Route;

Route::get('/', 'works\action\IndexAction@home');



Route::group(array('prefix' => '/user','filters'=>'is_login'),function(){

    Route::get('/home/abc', 'works\action\IndexAction@abc');

});


Route::group(array('prefix' => '/in','filters'=>'check'),function(){

    Route::get('/main/aaa', 'works\action\IndexAction@home');

});

Route::filters('is_login',function(){
   echo 'is_login';
});


Route::filters('check',function(){
    echo 'check';
});


Route::dispatch();