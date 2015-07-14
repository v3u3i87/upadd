<?php
/**
 * Created by PhpStorm.
 * User: v3u3
 * Date: 2015/4/7
 * Time: 15:19
 */

use Upadd\Bin\Route;

Route::get('/', 'Up\Action\Index@home');


Route::dispatch();