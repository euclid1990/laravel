<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api', 'prefix' => 'v1', 'as' => 'api.v1.'], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', 'LoginController@login')->name('login');
        Route::post('token/refresh', 'LoginController@refreshToken')->name('token.refresh');
        Route::post('register', 'LoginController@register')->name('register');
        Route::post('password/email', 'ForgotPasswordController@sendResetTokenEmail')->name('password.reset.email');
        Route::post('password/reset', 'ForgotPasswordController@reset')->name('password.reset');

        Route::group(['middleware' => ['auth:api']], function () {
            Route::post('logout', 'LoginController@logout')->name('logout');
        });
    });
});
