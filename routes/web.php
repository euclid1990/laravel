<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['permission:update-user,test', 'role:admin,user', 'permission:view-user']], function () {
    Route::resource('user', 'Web\UserController');
});

/*
|--------------------------------------------------------------------------
| Using the Cache Storage
|--------------------------------------------------------------------------
|
| Request to:
| - /cached/redis
| - /cached/memcached
| - ...
|
*/
Route::get('cache/{storage}', function (string $storage) {
    Cache::store($storage)->put('foo', 'baz', 60); // 1 Minute
    $value = Cache::store($storage)->get('foo');
    return $value;
});
