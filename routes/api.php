<?php

use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\MobileController;
use App\Http\Controllers\Mobile\ProductController;
use App\Http\Controllers\Mobile\HomeController;
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


Route::get('/Category', [MobileController::class, 'getCategories']);
Route::post('/updateStoreCategories', [MobileController::class, 'updateStoreCategories']);
Route::get('/allCategory', [MobileController::class, 'getAllCategories']);

Route::get('/getAddress', [MobileController::class, 'getAddress']);
Route::get('/getSingleAddress', [MobileController::class, 'getSingleAddress']);
Route::post('/addAddress', [MobileController::class, 'addAddress']);
Route::post('/updateAddress', [MobileController::class, 'updateAddress']);
Route::post('/deleteAddress', [MobileController::class, 'deleteAddress']);


Route::get('banner', [MobileController::class, 'banner']);


Route::group(['prefix' => 'products'], function ($router) {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('{id}', [ProductController::class, 'getProduct']);
});
Route::group(['prefix' => 'customer'], function ($router) {
    Route::group(['prefix' => 'products'], function ($router) {
        Route::post('create', [ProductController::class, 'create']);
        Route::post('update/{id}', [ProductController::class, 'update']);
        Route::get('/', [ProductController::class, 'myProducts']);
        Route::get('{id}', [ProductController::class, 'myProduct']);
        Route::post('request_swap', [ProductController::class, 'request_swap']);
    });
});

Route::get('filterable-attributes/{category_id}', 'App\Http\Controllers\Mobile\AttributeController@GetFilterableAttributes');
Route::group(['prefix' => 'attribute-options'], function ($router) {
    Route::post('add', 'App\Http\Controllers\Mobile\AttributeOptionController@add');
    Route::post('delete/{id}', 'App\Http\Controllers\Mobile\AttributeOptionController@delete');
});

Route::get('/getNewArrivals', [HomeController::class, 'getNewArrivals']);
Route::get('/getBestSellers', [HomeController::class, 'getBestSellers']);
Route::get('/getSpecialOffers', [HomeController::class, 'getSpecialOffers']);
Route::get('/getFeaturedItems', [HomeController::class, 'getFeaturedItems']);
Route::get('/getBrands', [HomeController::class, 'getBrands']);
Route::get('/getProductsByBrandID', [HomeController::class, 'getProductsByBrandID']);
Route::get('/getProductsByStoreID', [HomeController::class, 'getProductsByStoreID']);
Route::get('/getProductsByCategoryID', [HomeController::class, 'getProductsByCategoryID']);
Route::get('/globalSearch', [HomeController::class, 'globalSearch']);
Route::post('/viewItem', [MobileController::class, 'viewItem']);
Route::get('/getMostViewedProducts', [HomeController::class, 'getMostViewedProducts']);

// Route::get('/getCategory', 'App\Http\Controllers\Mobile\MobileController@getCategory');
// Route::get('/getSellingProduct', 'App\Http\Controllers\Mobile\MobileController@getSellingProduct');
// Route::get('/getBidProduct', 'App\Http\Controllers\Mobile\MobileController@getBidProduct');
// Route::get('/getSwapProduct', 'App\Http\Controllers\Mobile\MobileController@getSwapProduct');
// Route::get('/getTrendingProduct', 'App\Http\Controllers\Mobile\MobileController@getTrendingProduct');
// Route::get('/getTopStore', 'App\Http\Controllers\Mobile\MobileController@getTopStore');
// Route::get('/getProductBySubcategory', 'App\Http\Controllers\Mobile\MobileController@getProductBySubcategory');
// Route::get('/getLookingSwapProduct', 'App\Http\Controllers\Mobile\MobileController@getLookingSwapProduct');
// Route::get('/getPopularProduct', 'App\Http\Controllers\Mobile\MobileController@getPopularProduct');
// Route::get('/getAllProduct', 'App\Http\Controllers\Mobile\MobileController@getAllProduct');
// Route::post('/login', 'App\Http\Controllers\Mobile\AuthController@login');
// Route::get('/profile', 'App\Http\Controllers\Mobile\AuthController@profile');
// Route::post('/register', 'App\Http\Controllers\Mobile\AuthController@register');
// Route::post('/logout', 'App\Http\Controllers\Mobile\AuthController@logout');
// Route::post('/refresh', 'App\Http\Controllers\Mobile\AuthController@refresh');

// Route::post('/request_otp', 'App\Http\Controllers\Mobile\AuthController@requestOtp');

// Route::post('/verify_otp', 'App\Http\Controllers\Mobile\AuthController@verifyOtp');
// Route::post('/changepassword', 'App\Http\Controllers\Mobile\AuthController@changepassword');

// Route::post('/uploadproduct', 'App\Http\Controllers\Mobile\MobileController@uploadproduct');

// Route::get('/getStore', 'App\Http\Controllers\Mobile\MobileController@getStore');
