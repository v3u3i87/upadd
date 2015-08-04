<?php
/**
 * Created by PhpStorm.
 * User: v3u3
 * Date: 2015/4/7
 * Time: 15:19
 */

use Upadd\Bin\Route;

Route::get('/', 'works\action\IndexAction@home');

Route::any('/main/aaa', 'works\action\NameAction@aaa');

Route::get('/home/abc', 'works\action\IndexAction@abc');


Route::dispatch();