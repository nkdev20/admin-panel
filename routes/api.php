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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'user'], function () {
    Route::post('/signup', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::group(['middleware' => 'custom_auth'], function () {
        Route::post('/logout', 'AuthController@logout');
        Route::group(['middleware' => 'custom_auth'], function () {
            Route::get('/', 'AuthController@index');
            Route::get('/approve', 'AuthController@approveUser');
        });
    });
});



Route::post('/inventory', 'InventoryController@create')->middleware( 'custom_auth');