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
        Route::post('login', 'LoginController@login');
        Route::post('token/refresh', 'LoginController@refreshToken')->name('token.refresh');
        Route::post('logout', 'LoginController@logout');
        Route::post('register', 'LoginController@register');
        Route::post('password/email', 'ForgotPasswordController@sendResetTokenEmail')->name('password.reset.email');
        Route::post('password/reset', 'ForgotPasswordController@reset')->name('password.reset');
    });

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user/{id}', function ($id) {
            return \App\Models\User::find($id);
        });
    });
});
