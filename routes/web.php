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


// Route::group(['middleware'=>'api', 'prefix'=>'auth'], function ($router){
//     Route::post('/register', [ApiAuthController::class, 'register']);
//     Route::post('/login', [ApiAuthController::class, 'login']);
//     Route::get('/logout', [ApiAuthController::class, 'logout']);
// //    Route::get('/loggedInUser', [AuthController::class, 'loggedInUser']);
// //    Route::post('/deleteUser', [AccountController::class, 'deleteUser']);
// //    Route::post('/updateUser', [AccountController::class, 'updateUser']);

// });

// used by seller
Route::group(['middleware'=>'seller'], function ($router){
    Route::post('/createOrUpdateProduct', [App\Http\Controllers\ProductController::class, "createOrUpdateProduct"]);
    Route::get('/listProducts', [App\Http\Controllers\ProductController::class, "listProducts"]);
    Route::delete('/deleteProduct/{productId}', [App\Http\Controllers\ProductController::class, "deleteProduct"]);
});

// used by customer
Route::group(['middleware'=>'customer'], function ($router){
    Route::post('/addToCart', [App\Http\Controllers\CartController::class, "addToCart"]);
    Route::post('/updateProductQuantityInCart', [App\Http\Controllers\CartController::class, "updateProductQuantityInCart"]);
    Route::delete('/emptyCart/{userId}', [App\Http\Controllers\CartController::class, "emptyCart"]);
    Route::get('/getCart/{userId}', [App\Http\Controllers\CartController::class, "getCart"]);
});

// will be used by both customer and seller
Route::get('/getProduct/{productId}', [App\Http\Controllers\ProductController::class, "getProduct"]);









