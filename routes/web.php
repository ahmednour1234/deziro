<?php


use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BannerImageController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreProductController;
use App\Http\Controllers\UserController;

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



Route::group(['middleware' => ['guest']], function () {

    /**
     * Login Routes
     */
    Route::get('login',  [AuthController::class, 'index'])->name('auth.login');
    Route::post('login',  [AuthController::class, 'login'])->name('auth.login.perform');
});


Route::get('logout', [AuthController::class, 'logout']);


// Home
Route::get('/', [HomeController::class, 'listHome'])->name('admin.home.listHome');



// // Store
// // Route::get('/store',[StoreController::class,'listStore'])->name('admin.store.listStore');

Route::get('/requestStore', [StoreController::class, 'listRequestStore'])->name('admin.store.listRequestStore');
Route::get('/rejectedStore', [StoreController::class, 'listRejectedStore'])->name('admin.store.listRejectedStore');
Route::get('/store', [StoreController::class, 'listStore'])->name('admin.store.listStore');
Route::get('/createStore', [StoreController::class, 'createStore'])->name('admin.store.createStore');
Route::post('/addNewStore', [StoreController::class, 'addNewStore'])->name('admin.store.addNewStore');
Route::get('/editStore/{id}', [StoreController::class, 'editStore'])->name('admin.store.editStore');
Route::post('updateStore/{id}', [StoreController::class, 'updateStore'])->name('admin.store.updateStore');


//More Details
Route::get('/userDetail/{id}', [UserController::class, 'listUserDetail'])->name('admin.user.listUserDetail');
Route::get('/user', [UserController::class, 'listUser'])->name('admin.user.listUser');
Route::post('active/{id}', [UserController::class, 'is_active'])->name('admin.store.is_active');
Route::post('inactive/{id}', [UserController::class, 'is_inactive'])->name('admin.store.is_inactive');
Route::post('approve/{id}', [UserController::class, 'approve'])->name('admin.user.approve');
Route::post('reject/{id}', [UserController::class, 'reject'])->name('admin.user.reject');
//End









//Admins
Route::get('/adminn', [AdminController::class, 'listAdmin'])->name('admin.admin.listAdmin');
Route::post('/addNewAdmin', [AdminController::class, 'addNewAdmin'])->name('admin.admin.addNewAdmin');
Route::get('editAdmin/{id}', [AdminController::class, 'editAdmin'])->name('admin.admin.editAdmin');
Route::post('/updateAdmin/{id}', [AdminController::class, 'updateAdmin'])->name('admin.admin.updateAdmin');
//end Admin



//Category
Route::get('category', [CategoryController::class, 'listCategory'])->name('admin.category.listCategory');
Route::post('addNewCategory', [CategoryController::class, 'addNewCategory'])->name('admin.category.addNewCategory');
Route::get('editCategory/{id}', [CategoryController::class, 'editCategory'])->name('admin.category.editCategory');
Route::post('updateCategory/{id}', [CategoryController::class, 'updateCategory'])->name('admin.category.updateCategory');
Route::post('category_active/{id}', [CategoryController::class, 'is_active'])->name('admin.category.is_active');
Route::post('category_inactive/{id}', [CategoryController::class, 'is_inactive'])->name('admin.category.is_inactive');

Route::get('requesttochangecategories', [CategoryController::class, 'listrequesttochangecategories'])->name('admin.category.listrequesttochangecategories');
Route::post('rejectRequest/{id}', [CategoryController::class, 'rejectRequest'])->name('admin.category.rejectRequest');
Route::post('approveRequest/{id}', [CategoryController::class, 'approveRequest'])->name('admin.category.approveRequest');
//End

//Brand

Route::get('brand', [BrandController::class, 'listBrand'])->name('admin.brand.listBrand');
Route::post('addNewBrand', [BrandController::class, 'addNewBrand'])->name('admin.brand.addNewBrand');
Route::get('editBrand/{id}', [BrandController::class, 'editBrand'])->name('admin.brand.editBrand');
Route::post('updateBrand/{id}', [BrandController::class, 'updateBrand'])->name('admin.brand.updateBrand');
Route::post('brand_active/{id}', [BrandController::class, 'is_active'])->name('admin.brand.is_active');
Route::post('brand_inactive/{id}', [BrandController::class, 'is_inactive'])->name('admin.brand.is_inactive');

//End Brand


//Notification
Route::get('notification', [NotificationController::class, 'listNotification'])->name('admin.notification.listNotification');
Route::post('addNewNotification', [NotificationController::class, 'addNewNotification'])->name('admin.notification.addNewNotification');
//End Notification


Route::get('banner', [BannerController::class, 'listBanner'])->name('admin.banner.listBanner');



Route::get('/bannerImage/{id}', [BannerImageController::class, 'fileCreate'])->name('admin.bannerImage.fileCreate');
Route::post('/bannerImage/store/{id}', [BannerImageController::class, 'fileStore'])->name('admin.bannerImage.fileStore');
Route::post('/bannerImage/delete', [BannerImageController::class, 'fileDestroy'])->name('admin.bannerImage.fileDestroy');
Route::get('/bannerImage/delete/{id}', [BannerImageController::class, 'fileDelete'])->name('admin.bannerImage.fileDelete');



/**
 * Attributes routes.
 */
Route::get('/attributes', [AttributeController::class, 'index'])->defaults('_config', [
    'view' => 'admin.attributes.index',
])->name('admin.attributes.index');

Route::get('/attributes/create', [AttributeController::class, 'create'])->defaults('_config', [
    'view' => 'admin.attributes.create',
])->name('admin.attributes.create');

Route::post('/attributes/create', [AttributeController::class, 'store'])->defaults('_config', [
    'redirect' => 'admin.attributes.index',
])->name('admin.attributes.store');

Route::get('/attributes/edit/{id}', [AttributeController::class, 'edit'])->defaults('_config', [
    'view' => 'admin.attributes.edit',
])->name('admin.attributes.edit');

Route::post('/attributes/edit/{id}', [AttributeController::class, 'update'])->defaults('_config', [
    'redirect' => 'admin.attributes.index',
])->name('admin.attributes.update');

Route::post('/attributes/delete/{id}', [AttributeController::class, 'destroy'])->name('admin.attributes.delete');

// Route::post('/attributes/massdelete', [AttributeController::class, 'massDestroy'])->name('admin.attributes.massdelete');










//store selling product
Route::get('storeProduct', [ProductController::class, 'listStoreProduct'])->name('admin.storeProduct.listStoreProduct');

Route::post('activeProduct/{id}', [ProductController::class, 'is_active'])->name('admin.product.is_active');
Route::post('inactiveProduct/{id}', [ProductController::class, 'is_inactive'])->name('admin.product.is_inactive');
Route::get('productDetail/{id}', [ProductController::class, 'productDetail'])->name('admin.product.productDetail');

Route::get('featuredProducts', [ProductController::class, 'listFeaturedProducts'])->name('admin.product.listFeaturedProducts');
Route::post('addFeaturedProduct', [ProductController::class, 'addFeaturedProduct'])->name('admin.product.addFeaturedProduct');
Route::post('deleteFeaturedProduct/{id}', [ProductController::class, 'deleteFeaturedProduct'])->name('admin.product.deleteFeaturedProduct');



// //store bid Product
// Route::get('storeBidProduct', [StoreProductController::class, 'listBidProduct'])->name('admin.storeProduct.listBidProduct');
// Route::post('addNewBidProduct',[StoreProductController::class,'addNewBidProduct'])->name('admin.storeProduct.addNewBidProduct');
// Route::get('editBidProduct/{id}', [StoreProductController::class, 'editBidProduct'])->name('admin.storeProduct.editBidProduct');
// Route::post('updateBidProduct/{id}',[StoreProductController::class,'updateBidProduct'])->name('admin.storeProduct.updateBidProduct');


// Route::get('/productImages/{id}', [ProductImagesController::class, 'fileCreate'])->name('admin.productImages.fileCreate');
// Route::post('/productImages/store/{id}', [ProductImagesController::class,'fileStore'])->name('admin.productImages.fileStore');
// Route::post('/productImages/delete', [ProductImagesController::class,'fileDestroy'])->name('admin.productImages.fileDestroy');
// Route::get('/productImages/delete/{id}', [ProductImagesController::class,'fileDelete'])->name('admin.productImages.fileDelete');



// //store Product
// Route::get('/storeProduct/{id}',[StoreController::class,'listStoreProduct'])->name('admin.store.listStoreProduct');

// //individual Product
// Route::get('/individualProduct/{id}',[IndividualController::class,'listIndividualProduct'])->name('admin.store.listIndividualProduct');




// //info
// Route::get('/info',[InfoController::class,'listInfo'])->name('admin.info.listInfo');


// //setting
// Route::get('/setting',[SettingController::class,'listSetting'])->name('admin.setting.listSetting');

// //review
// Route::get('/review',[ReviewController::class,'listReview'])->name('admin.review.listReview');


// //Orders
// Route::get('/pendingOrder',[OrderController::class,'listPendingOrder'])->name('admin.order.listPendingOrder');
// Route::get('/shippingOrder',[OrderController::class,'listShippingOrder'])->name('admin.order.listShippingOrder');
// Route::get('/canceledOrder',[OrderController::class,'listCanceledOrder'])->name('admin.order.listCanceledOrder');
// Route::get('/deliverydOrder',[OrderController::class,'listDeliverydOrder'])->name('admin.order.listDeliverydOrder');



// //Notification
// Route::get('/notification',[NotificationController::class,'listNotification'])->name('admin.notification.listNotification');
