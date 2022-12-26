<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\AccountController;


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
Route::post('register', 'App\Http\Controllers\Auth\ApiAuthController@register');
Route::post('login', 'App\Http\Controllers\Auth\ApiAuthController@login');


Route::middleware('auth:api')->group(function () {
    Route::post('checkAccess', 'App\Http\Controllers\Auth\ApiAuthController@checkAccess');
    Route::get('fetchUser', 'App\Http\Controllers\AccountController@fetchUser');
    Route::post('logout', 'App\Http\Controllers\Auth\ApiAuthController@logout');

    
    Route::post('createOrUpdateProduct', 'App\Http\Controllers\ThirdPartyApiController@createOrUpdateProduct')->middleware("api.seller");
    Route::get('listProducts', 'App\Http\Controllers\ThirdPartyApiController@listProducts')->middleware("api.seller");
    Route::get('getProduct/{productId}', 'App\Http\Controllers\ThirdPartyApiController@getProduct')->middleware("api.seller");
});
