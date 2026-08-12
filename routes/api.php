<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrontendController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PixelGtmController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\SocialMedaiaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\BuyController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;

use App\Http\Controllers\Api\WishlistController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('web')->get('/csrf-token', [AuthController::class, 'getCsrfToken']);
Route::post('/register', [AuthController::class, 'userRegister'])->name('user.register');
Route::post('/login', [AuthController::class, 'userLogin'])->name('user.login');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);
 Route::get('/order-print/{order_id}', [OrderController::class, 'order_print']);

Route::middleware(['auth:sanctum','api'])->name('api.')->group(function () {

    Route::post('logout', [AuthController::class, 'userLogout'])->name('user.logout');

    //User Dashboard
    Route::get('/dashboard-overview', [FrontendController::class, 'dashboardOverview']);
    Route::get('/user-profile', [FrontendController::class, 'userProfile'])->name('user.user-profile');
    Route::get('/user-order-history', [FrontendController::class, 'userOrderHistory'])->name('user.user-order-history');
    Route::post('/user-settings', [FrontendController::class, 'userSettings'])->name('user.user-settings');
    Route::post('/user-update-password', [FrontendController::class, 'userUpdatePassword'])->name('user.user-update-password');
    Route::get('/payments', [FrontendController::class, 'payments']);
    Route::get('/refunds', [FrontendController::class, 'refunds']);
    Route::get('/delivery', [FrontendController::class, 'delivery']);
    Route::get('/order-status', [FrontendController::class, 'orderStatus']);
    Route::get('/invoice/{invoice_id}', [FrontendController::class, 'invoice']);
   

    //Wishlist
    Route::get('/wishlists', [WishlistController::class, 'wishlist'])->name('user.wishlist');
    Route::post('/add-to-wishlist', [WishlistController::class, 'addToWishlist'])->name('user.add-to-wishlist');
    Route::post('/remove-wishlist', [WishlistController::class, 'removeFromWishlist'])->name('user.remove-from-wishlist');

    // cart
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
    Route::post('/product-add-to-cart', [CartController::class, 'productAddToCart'])->name('product-add-to-cart');
    Route::get('/get-qty', [CartController::class, 'productGetqty'])->name('product-Getqty');
    Route::get('/cart-products', [CartController::class, 'cartProducts'])->name('cart-products');
    Route::get('/cart-order-products', [CartController::class, 'cartOrderProducts']);
    Route::post('/cart-remove/{id}', [CartController::class, 'cartRemove']);
    Route::post('/cart-details-update/{id}', [CartController::class, 'cartDetailsUpdate']);
    Route::post('/cart-details-delete/{id}', [CartController::class, 'cartDetailsDelete']);
    
    // buy
    Route::post('/product-buy-now', [BuyController::class, 'productAddToBuy'])->name('product-buy-now');
    Route::get('/buy-products', [BuyController::class, 'buyProducts'])->name('buy-products');

    // add product review
    Route::post('/add-product-review', [ProductController::class, 'addProductReview'])->name('addProductReview');

    //Order place
    Route::post('/order-place', [OrderController::class, 'orderPlace'])->name('user.place-order');
    
    Route::post('/buy-order-place', [OrderController::class, 'buyorderPlace'])->name('user.buy-place-order'); 

    // ticket
    Route::post('/ticket-store',[TicketController::class,'ticket_store'])->name('ticket.store');
    Route::get('/ticket-list',[TicketController::class,'ticket_list'])->name('ticket.list');
    Route::get('/ticket-replay-list/{ticket_id}',[TicketController::class,'ticket_reply_list'])->name('ticket.replay.list');
    Route::post('/ticket-replay-submit',[TicketController::class,'ticket_reply_submit'])->name('ticket.replay.submit');
    
    // order ticket
    Route::post('/order-ticket-store',[TicketController::class,'orderticket_store'])->name('orderticket.store');

    // banks
    Route::get('/bank-lists',[SettingController::class,'bank_lists'])->name('bank.list');
    // payment
    Route::get('/payment/{invoice_id}', [OrderController::class, 'payment'])->name('payment');
    // payment submit
    Route::post('/payment/submit/{invoice_id}', [OrderController::class, 'payment_submit'])->name('payment.submit');

});

Route::name('api.')->group(function () {

    // website info
    Route::get('/settings', [SettingController::class, 'settings'])->name('settings');

    // contact
    Route::get('/contact', [SettingController::class, 'contact'])->name('contact');


    // slider
    Route::get('/mainslider', [SliderController::class, 'main_sliders'])->name('mainsliders');
    Route::get('/galleryslider', [SliderController::class, 'gallery_slider'])->name('galleryslider');
    Route::get('/featuredbanner', [SliderController::class, 'featured_banner'])->name('featuredbanner');

    // pixel and gtm
    Route::get('/pixel', [PixelGtmController::class, 'pixel'])->name('pixel');
    Route::get('/gtm', [PixelGtmController::class, 'gtm'])->name('gtm');

    // pages
    Route::get('/pages', [PageController::class, 'pages'])->name('pages');

    // social media
    Route::get('/social-media',  [SocialMedaiaController::class, 'socialMedia'])->name('socialmedia');

    // category
    Route::get('/categories', [CategoryController::class, 'categories'])->name('categories');
    
    Route::get('/menu-categories', [CategoryController::class, 'menuCategories'])->name('menuCategories');

    // subcategory
    Route::get('/subcategories-by-category/{slug}', [CategoryController::class, 'subcategoriesByCategory'])->name('subcategories-by-category');

    // childcategory
    Route::get('/childcategories-by-subcategory/{slug}', [CategoryController::class, 'childcategoriesBySubcategory'])->name('user.childcategories-by-subcategory');


    // shipping area
    Route::get('/shipping-area', [FrontendController::class, 'shippingArea'])->name('shipping-area');


    // product
    Route::get('/front-category-products', [ProductController::class, 'frontCategoryProducts'])->name('front-category-products');
    Route::get('/search-products/{keyword?}', [ProductController::class, 'searchProducts'])->name('search-products');
    Route::get('/product-details/{slug}', [ProductController::class, 'productDetails'])->name('product-details');
    Route::get('/related-products/{slug}', [ProductController::class, 'relatedProducts'])->name('related-products');
    Route::get('/category-products/{slug}', [ProductController::class, 'categoryProducts'])->name('category-products');
    Route::get('/subcategory-products/{slug}', [ProductController::class, 'subcategoryProducts'])->name('subcategory-products');
    Route::get('/childcategory-products/{slug}', [ProductController::class, 'childcategoryProducts'])->name('childcategory-products');
    Route::get('/product-bulkquantities/{product_id}', [ProductController::class, 'bulkquantities'])->name('product-bulkquantities');
    Route::get('/flash-sale', [ProductController::class, 'flashSale']);

    // product review list
    Route::get('/product-review-list', [ProductController::class, 'productreviewList'])->name('productreviewList');

    // coupon
    Route::get('/apply/coupon', [OrderController::class, 'apply_coupon'])->name('applycoupon.order');


    // order track
    Route::get('/order-track/{invoice_id}', [OrderController::class, 'orderTrack'])->name('order-track');
    
    // district and thana
    Route::get('/district', [FrontendController::class, 'district']);
    Route::get('/thana', [FrontendController::class, 'thana']);
});
