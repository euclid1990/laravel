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

Route::group(['namespace' => 'Web'], function () {
    Route::get('/', 'HomeController@index')->name('home');

    Route::group(['middleware' => ['auth']], function () {
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    });
    // Login
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    // Register
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
    // Password reset
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    // Email verify
    Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
    // Import, export file csv, excel
    Route::group(['middleware' => 'auth'], function () {
        Route::get('import', 'ImportController@index')->name('import.index');
        Route::post('import', 'ImportController@import')->name('import.create');
    });
    Route::get('export', 'ExportController@index')->name('export.index');
    Route::post('export', 'ExportController@export')->name('export.create');
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

Route::get('/client/{any}', function () {
    return view('client');
})->where('any', '.*');
