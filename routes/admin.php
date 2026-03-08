<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Catalog\AttributeController;
use App\Http\Controllers\Admin\Catalog\AttributeGroupController;
use App\Http\Controllers\Admin\Catalog\CategoryController;
use App\Http\Controllers\Admin\Catalog\DownloadController;
use App\Http\Controllers\Admin\Catalog\FilterController;
use App\Http\Controllers\Admin\Catalog\ManufacturerController;
use App\Http\Controllers\Admin\Catalog\OptionController;
use App\Http\Controllers\Admin\Catalog\ProductController;
use App\Http\Controllers\Admin\Catalog\ReviewController;
use App\Http\Controllers\Admin\Catalog\SubscriptionPlanController;
use App\Http\Controllers\Admin\Customer\CustomFieldController;
use App\Http\Controllers\Admin\Customer\CustomerController;
use App\Http\Controllers\Admin\Customer\CustomerGroupController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Design\BannerController;
use App\Http\Controllers\Admin\Design\LayoutController;
use App\Http\Controllers\Admin\Design\SeoUrlController;
use App\Http\Controllers\Admin\Localisation\CountryController;
use App\Http\Controllers\Admin\Localisation\CurrencyController;
use App\Http\Controllers\Admin\Localisation\GeoZoneController;
use App\Http\Controllers\Admin\Localisation\LanguageController;
use App\Http\Controllers\Admin\Localisation\OrderStatusController;
use App\Http\Controllers\Admin\Localisation\ReturnStatusController;
use App\Http\Controllers\Admin\Localisation\StockStatusController;
use App\Http\Controllers\Admin\Localisation\TaxClassController;
use App\Http\Controllers\Admin\Localisation\TaxRateController;
use App\Http\Controllers\Admin\Localisation\ZoneController;
use App\Http\Controllers\Admin\Marketing\AffiliateController;
use App\Http\Controllers\Admin\Marketing\CouponController;
use App\Http\Controllers\Admin\Report\CustomerReportController;
use App\Http\Controllers\Admin\Report\ProductReportController;
use App\Http\Controllers\Admin\Sale\OrderController;
use App\Http\Controllers\Admin\Sale\ReturnController;
use App\Http\Controllers\Admin\Sale\SubscriptionController;
use App\Http\Controllers\Admin\Setting\SettingController;
use App\Http\Controllers\Admin\Tool\ErrorLogController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\User\UserGroupController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (guests only)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(['auth:admin', 'admin.permission'])->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Catalog
        Route::prefix('catalog')->name('catalog.')->group(function () {
            Route::resource('product', ProductController::class);
            Route::resource('category', CategoryController::class);
            Route::resource('manufacturer', ManufacturerController::class);
            Route::resource('attribute-group', AttributeGroupController::class);
            Route::resource('attribute', AttributeController::class);
            Route::resource('option', OptionController::class);
            Route::resource('filter', FilterController::class);
            Route::resource('download', DownloadController::class);
            Route::resource('review', ReviewController::class)->except(['create', 'store']);
            Route::resource('subscription-plan', SubscriptionPlanController::class);
        });

        // Sales
        Route::prefix('sale')->name('sale.')->group(function () {
            Route::resource('order', OrderController::class)->except(['create', 'store', 'edit', 'update']);
            Route::post('order/{order}/history', [OrderController::class, 'addHistory'])->name('order.history');

            Route::resource('return', ReturnController::class)->except(['create', 'store', 'edit', 'update']);
            Route::post('return/{return}/history', [ReturnController::class, 'addHistory'])->name('return.history');

            Route::resource('subscription', SubscriptionController::class)->only(['index', 'show']);
            Route::patch('subscription/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
        });

        // Customers
        Route::prefix('customer')->name('customer.')->group(function () {
            Route::resource('customer', CustomerController::class);
            Route::post('customer/{customer}/login-as', [CustomerController::class, 'loginAs'])->name('customer.login-as');
            Route::resource('customer-group', CustomerGroupController::class);
            Route::resource('custom-field', CustomFieldController::class);
        });

        // Design
        Route::prefix('design')->name('design.')->group(function () {
            Route::resource('banner', BannerController::class);
            Route::resource('layout', LayoutController::class);
            Route::resource('seo-url', SeoUrlController::class);
        });

        // Marketing
        Route::prefix('marketing')->name('marketing.')->group(function () {
            Route::resource('coupon', CouponController::class);
            Route::resource('affiliate', AffiliateController::class)->only(['index', 'show', 'destroy']);
            Route::patch('affiliate/{affiliate}/approve', [AffiliateController::class, 'approve'])->name('affiliate.approve');
        });

        // Localisation
        Route::prefix('localisation')->name('localisation.')->group(function () {
            Route::resource('currency', CurrencyController::class);
            Route::resource('language', LanguageController::class);
            Route::resource('country', CountryController::class);
            Route::resource('zone', ZoneController::class);
            Route::resource('geo-zone', GeoZoneController::class);
            Route::resource('tax-class', TaxClassController::class);
            Route::resource('tax-rate', TaxRateController::class);
            Route::resource('order-status', OrderStatusController::class);
            Route::resource('return-status', ReturnStatusController::class);
            Route::resource('stock-status', StockStatusController::class);
        });

        // Reports
        Route::prefix('report')->name('report.')->group(function () {
            Route::get('product', [ProductReportController::class, 'index'])->name('product');
            Route::get('customer', [CustomerReportController::class, 'index'])->name('customer');
        });

        // Settings
        Route::prefix('setting')->name('setting.')->group(function () {
            Route::get('general', [SettingController::class, 'general'])->name('general');
            Route::post('general', [SettingController::class, 'saveGeneral'])->name('general.save');
            Route::get('store', [SettingController::class, 'store'])->name('store');
            Route::post('store', [SettingController::class, 'saveStore'])->name('store.save');
            Route::get('localisation', [SettingController::class, 'localisation'])->name('localisation');
            Route::post('localisation', [SettingController::class, 'saveLocalisation'])->name('localisation.save');
            Route::get('option', [SettingController::class, 'option'])->name('option');
            Route::post('option', [SettingController::class, 'saveOption'])->name('option.save');
        });

        // Tools
        Route::prefix('tool')->name('tool.')->group(function () {
            Route::get('error-log', [ErrorLogController::class, 'index'])->name('error-log');
            Route::delete('error-log', [ErrorLogController::class, 'clear'])->name('error-log.clear');
        });

        // Users
        Route::prefix('user')->name('user.')->group(function () {
            Route::resource('user', UserController::class);
            Route::resource('user-group', UserGroupController::class);
        });
    });
});
