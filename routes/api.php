<?php

use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\MobileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Auth::routes();


Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout');
});
Route::get('/profile', [AuthController::class, 'profile']);
Route::post('/updateProfile', [AuthController::class, 'updateProfile']);
Route::post('/changePassword', [AuthController::class, 'changePassword']);


Route::get('/categorie', [MobileController::class, 'getCategories']);

Route::get('/getAddress', [MobileController::class, 'getAddress']);
Route::get('/getSingleAddress', [MobileController::class, 'getSingleAddress']);
Route::post('/addAddress', [MobileController::class, 'addAddress']);
Route::post('/updateAddress', [MobileController::class, 'updateAddress']);
Route::post('/deleteAddress', [MobileController::class, 'deleteAddress']);


Route::get('banner', [MobileController::class, 'banner']);




// Route::get('/getCategory', 'App\Http\Controllers\App\MobileController@getCategory');
// Route::get('/getSellingProduct', 'App\Http\Controllers\App\MobileController@getSellingProduct');
// Route::get('/getBidProduct', 'App\Http\Controllers\App\MobileController@getBidProduct');
// Route::get('/getSwapProduct', 'App\Http\Controllers\App\MobileController@getSwapProduct');
// Route::get('/getTrendingProduct', 'App\Http\Controllers\App\MobileController@getTrendingProduct');
// Route::get('/getTopStore', 'App\Http\Controllers\App\MobileController@getTopStore');
// Route::get('/getProductBySubcategory', 'App\Http\Controllers\App\MobileController@getProductBySubcategory');
// Route::get('/getLookingSwapProduct', 'App\Http\Controllers\App\MobileController@getLookingSwapProduct');
// Route::get('/getPopularProduct', 'App\Http\Controllers\App\MobileController@getPopularProduct');
// Route::get('/getAllProduct', 'App\Http\Controllers\App\MobileController@getAllProduct');
// Route::post('/login', 'App\Http\Controllers\App\AuthController@login');
// Route::get('/profile', 'App\Http\Controllers\App\AuthController@profile');
// Route::post('/register', 'App\Http\Controllers\App\AuthController@register');
// Route::post('/logout', 'App\Http\Controllers\App\AuthController@logout');
// Route::post('/refresh', 'App\Http\Controllers\App\AuthController@refresh');

// Route::post('/request_otp', 'App\Http\Controllers\App\AuthController@requestOtp');

// Route::post('/verify_otp', 'App\Http\Controllers\App\AuthController@verifyOtp');
// Route::post('/changepassword', 'App\Http\Controllers\App\AuthController@changepassword');

// Route::post('/uploadproduct', 'App\Http\Controllers\App\MobileController@uploadproduct');

// Route::get('/getStore', 'App\Http\Controllers\App\MobileController@getStore');

