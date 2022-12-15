<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ApiAuthController;



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

Route::get('/', function () {
    return view('welcome');
//    return "hi";
});

//Route::post('/createUser', [AccountController::class, 'createUser']);



Route::get('/fetchUser', [AccountController::class, 'fetchUser']);

//Route::get('/fetchAllUsers', [AccountController::class, 'fetchAllUser']);

//Route::prefix('admin')->middleware('auth')->group(funcion(){
//    Route::post('/createRole', [AccountController::class, 'createRole']);
//
//});


Route::get('/verifyEmail/{userid}/{otp}', function ($userid, $otp) {

    $accController = new AccountController();
    $accController->validateUserAcc($userid, $otp);
});


Route::group(['middleware'=>'api', 'prefix'=>'auth'], function ($router){
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::get('/logout', [ApiAuthController::class, 'logout']);
//    Route::get('/loggedInUser', [AuthController::class, 'loggedInUser']);
//    Route::post('/deleteUser', [AccountController::class, 'deleteUser']);
//    Route::post('/updateUser', [AccountController::class, 'updateUser']);

});






