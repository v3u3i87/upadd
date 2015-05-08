<?php
/**
 * Created by PhpStorm.
 * User: v3u3
 * Date: 2015/4/7
 * Time: 15:19
 */

use Upadd\Bin\Route;

Route::get('/', 'Job@index');

Route::get('/test', 'Job@test');




Route::get('(:all)', function($fu) {
    echo '未匹配到路由<br>'.$fu;
});

Route::dispatch();