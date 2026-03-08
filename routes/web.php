<?php

use App\Http\Controllers\Storefront\Auth\LoginController;
use App\Http\Controllers\Storefront\Auth\RegisterController;
use App\Http\Controllers\Storefront\Account\AccountController;
use App\Http\Controllers\Storefront\Account\AddressController;
use App\Http\Controllers\Storefront\Account\DownloadController;
use App\Http\Controllers\Storefront\Account\NewsletterController;
use App\Http\Controllers\Storefront\Account\OrderController;
use App\Http\Controllers\Storefront\Account\ReturnController;
use App\Http\Controllers\Storefront\Account\WishlistController;
use App\Http\Controllers\Storefront\Cart\CartController;
use App\Http\Controllers\Storefront\Catalog\CategoryController;
use App\Http\Controllers\Storefront\Catalog\HomeController;
use App\Http\Controllers\Storefront\Catalog\ManufacturerController;
use App\Http\Controllers\Storefront\Catalog\ProductController;
use App\Http\Controllers\Storefront\Catalog\SearchController;
use App\Http\Controllers\Storefront\Checkout\CheckoutController;
use App\Http\Controllers\Storefront\Cms\BlogController;
use App\Http\Controllers\Storefront\Content\ContactController;
use App\Http\Controllers\Storefront\Content\InformationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Storefront
|--------------------------------------------------------------------------
*/

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Catalog
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::post('/product/{id}/review', [ProductController::class, 'storeReview'])->name('product.review.store');
Route::get('/manufacturer', [ManufacturerController::class, 'index'])->name('manufacturer.index');
Route::get('/manufacturer/{id}', [ManufacturerController::class, 'show'])->name('manufacturer.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Cart
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/{cartId}', [CartController::class, 'update'])->name('update');
    Route::delete('/{cartId}', [CartController::class, 'remove'])->name('remove');
});

// Checkout
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/shipping-address', [CheckoutController::class, 'shippingAddress'])->name('shipping-address');
    Route::post('/shipping-address', [CheckoutController::class, 'saveShippingAddress']);
    Route::get('/shipping-method', [CheckoutController::class, 'shippingMethod'])->name('shipping-method');
    Route::post('/shipping-method', [CheckoutController::class, 'saveShippingMethod']);
    Route::get('/payment-method', [CheckoutController::class, 'paymentMethod'])->name('payment-method');
    Route::post('/payment-method', [CheckoutController::class, 'savePaymentMethod']);
    Route::get('/confirm', [CheckoutController::class, 'confirm'])->name('confirm');
    Route::post('/place', [CheckoutController::class, 'place'])->name('place');
    Route::get('/success/{orderId}', [CheckoutController::class, 'success'])->name('success');
    Route::get('/failure', [CheckoutController::class, 'failure'])->name('failure');
});

// Auth
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:web');

// Account (requires auth:web middleware, handled in controller constructor)
Route::prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');

    // Orders
    Route::prefix('orders')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    });

    // Addresses
    Route::prefix('addresses')->name('address.')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('index');
        Route::get('/create', [AddressController::class, 'create'])->name('create');
        Route::post('/', [AddressController::class, 'store'])->name('store');
        Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('edit');
        Route::put('/{address}', [AddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
    });

    // Wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/{productId}', [WishlistController::class, 'add'])->name('add');
        Route::delete('/{productId}', [WishlistController::class, 'remove'])->name('remove');
    });

    // Returns
    Route::prefix('returns')->name('return.')->group(function () {
        Route::get('/', [ReturnController::class, 'index'])->name('index');
        Route::get('/create', [ReturnController::class, 'create'])->name('create');
        Route::post('/', [ReturnController::class, 'store'])->name('store');
        Route::get('/{id}', [ReturnController::class, 'show'])->name('show');
    });

    // Downloads
    Route::prefix('downloads')->name('download.')->group(function () {
        Route::get('/', [DownloadController::class, 'index'])->name('index');
        Route::get('/{downloadId}', [DownloadController::class, 'download'])->name('get');
    });

    // Newsletter
    Route::patch('/newsletter', [NewsletterController::class, 'update'])->name('newsletter.update');
});

// CMS / Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{categoryId}', [BlogController::class, 'category'])->name('category');
    Route::get('/{id}', [BlogController::class, 'show'])->name('show');
});

// Information pages
Route::get('/information/{id}', [InformationController::class, 'show'])->name('information.show');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
