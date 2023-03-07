<?php


use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BannerImageController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AttributeController;
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
    Route::get('login',  [AuthController::class, 'index'])->name('login');
    Route::post('login',  [AuthController::class, 'login'])->name('login.perform');
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
Route::get('category', [CategorieController::class, 'listCategory'])->name('admin.category.listCategory');
Route::post('addNewCategory', [CategorieController::class, 'addNewCategory'])->name('admin.category.addNewCategory');
Route::get('editCategory/{id}', [CategorieController::class, 'editCategory'])->name('admin.category.editCategory');
Route::post('updateCategory/{id}', [CategorieController::class, 'updateCategory'])->name('admin.category.updateCategory');
Route::post('category_active/{id}', [CategorieController::class, 'is_active'])->name('admin.category.is_active');
Route::post('category_inactive/{id}', [CategorieController::class, 'is_inactive'])->name('admin.category.is_inactive');
//End




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



// Route::get('/deleteCategory/{id}',[CategoryController::class,'deleteCategory'])->name('admin.category.deleteCategory');
// Route::post('category_active/{id}', [CategoryController::class, 'is_active'])->name('admin.category.is_active');
// Route::post('category_inactive/{id}', [CategoryController::class, 'is_inactive'])->name('admin.category.is_inactive');

// Route::get('categoryIcons/{id}',[IconCategoryController::class,'displayCategoryIcon'])->name('admin.categoryicon.displayCategoryIcon');
// Route::post('addNewiconpath',[IconCategoryController::class,'addNewiconpath'])->name('admin.categoryicon.addNewiconpath');
// Route::post('addNewicondarkpath',[IconCategoryController::class,'addNewicondarkpath'])->name('admin.categoryicon.addNewicondarkpath');
// Route::post('addNewloaderpath',[IconCategoryController::class,'addNewloaderpath'])->name('admin.categoryicon.addNewloaderpath');
// Route::post('addNewloaderdarkpath',[IconCategoryController::class,'addNewloaderdarkpath'])->name('admin.categoryicon.addNewloaderdarkpath');
// Route::post('addNewiconpathclicked',[IconCategoryController::class,'addNewiconpathclicked'])->name('admin.categoryicon.addNewiconpathclicked');
// // Route::post('addNewiconpathdark',[IconCategoryController::class,'addNewiconpathdark'])->name('admin.categoryicon.addNewiconpathdark');
// Route::post('addNewiconpathdarkclicked',[IconCategoryController::class,'addNewiconpathdarkclicked'])->name('admin.categoryicon.addNewiconpathdarkclicked');



// //SubCategory
// Route::get('category', [CategoryController::class, 'listCategory'])->name('admin.category.listCategory');
// Route::post('addNewSubCategory',[CategoryController::class,'addNewSubCategory'])->name('admin.category.addNewSubCategory');
// Route::get('editSubCategory/{id}', [CategoryController::class, 'editSubCategory'])->name('admin.category.editSubCategory');
// Route::post('updateSubCategory/{id}',[CategoryController::class,'updateSubCategory'])->name('admin.category.updateSubCategory');
// Route::get('/deleteSubcategory/{id}',[CategoryController::class,'deleteSubcategory'])->name('admin.category.deleteSubcategory');
// Route::post('category_active/{id}', [CategoryController::class, 'is_active'])->name('admin.category.is_active');
// Route::post('category_inactive/{id}', [CategoryController::class, 'is_inactive'])->name('admin.category.is_inactive');


// //Products
// Route::get('product', [ProductController::class, 'listRequestProduct'])->name('admin.product.listRequestProduct');

// Route::post('/reject_product/{id}',[ProductController::class,'reject_product'])->name('admin.product.reject_product');
// Route::post('/approve_product/{id}',[ProductController::class,'approve_product'])->name('admin.product.approve_product');


// Route::get('rejectedProduct', [ProductController::class, 'listRejectedProduct'])->name('admin.product.listRejectedProduct');


// //SellingProduct
// Route::get('sellingProduct', [SellingProductController::class, 'listSellingProduct'])->name('admin.product.listSellingProduct');
// // Route::post('addNewSellingProduct',[SellingProductController::class,'addNewSellingProduct'])->name('admin.product.addNewSellingProduct');
// // Route::get('editSellingProduct/{id}', [SellingProductController::class, 'editSellingProduct'])->name('admin.product.editSellingProduct');
// // Route::post('updateSellingProduct/{id}',[SellingProductController::class,'updateSellingProduct'])->name('admin.product.updateSellingProduct');
// // Route::get('/deleteSellingProduct/{id}',[SellingProductController::class,'deleteSellingProduct'])->name('admin.product.deleteSellingProduct');
// Route::get('sellingProductDetail/{id}', [SellingProductController::class, 'SellingProductDetail'])->name('admin.product.SellingProductDetail');




// //Bid Product
// Route::get('bidProduct', [BidProductController::class, 'listBidProduct'])->name('admin.product.listBidProduct');
// // Route::post('addNewBidProduct',[BidProductController::class,'addNewBidProduct'])->name('admin.product.addNewBidProduct');
// // Route::get('editBidProduct/{id}', [BidProductController::class, 'editBidProduct'])->name('admin.product.editBidProduct');
// // Route::post('updateBidProduct/{id}',[BidProductController::class,'updateBidProduct'])->name('admin.product.updateBidProduct');
// // Route::get('/deleteBidProduct/{id}',[BidProductController::class,'deleteBidProduct'])->name('admin.product.deleteBidProduct');
// Route::get('bidProductDetail/{id}', [BidProductController::class, 'bidProductDetail'])->name('admin.product.bidProductDetail');


// //Bid
// Route::get('viewBidProduct/{id}', [BidProductController::class, 'viewBidProduct'])->name('admin.product.viewBidProduct');


// //Swap Product
// Route::get('swapProduct', [SwapProductController::class, 'listSwapProduct'])->name('admin.product.listSwapProduct');
// // Route::get('editSwapProduct/{id}', [SwapProductController::class, 'editSwapProduct'])->name('admin.product.editSwapProduct');
// // Route::post('updateSwapProduct/{id}',[SwapProductController::class,'updateSwapProduct'])->name('admin.product.updateSwapProduct');
// // Route::get('/deleteSwapProduct/{id}',[SwapProductController::class,'deleteSwapProduct'])->name('admin.product.deleteSwapProduct');
// Route::get('viewSwapProduct/{id}', [SwapProductController::class, 'viewSwapProduct'])->name('admin.product.viewSwapProduct');
// Route::get('swapProductDetail/{id}', [SwapProductController::class, 'swapProductDetail'])->name('admin.product.swapProductDetail');

// //store selling product
// Route::get('storeSellingProduct', [StoreProductController::class, 'listSellingProduct'])->name('admin.storeProduct.listSellingProduct');
// Route::post('addNewSellingProduct',[StoreProductController::class,'addNewSellingProduct'])->name('admin.storeProduct.addNewSellingProduct');
// Route::get('editSellingProduct/{id}', [StoreProductController::class, 'editSellingProduct'])->name('admin.storeProduct.editSellingProduct');
// Route::post('updateSellingProduct/{id}',[StoreProductController::class,'updateSellingProduct'])->name('admin.storeProduct.updateSellingProduct');


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
