<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    $products = \App\Models\Product::with('category')
        ->where('status', 'active')
        ->latest()
        ->take(3)
        ->get();
    return view('welcome', compact('products'));
})->name('welcome');

// Temporary route for deployment
Route::get('/run-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrate = \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        $migrate = $e->getMessage();
    }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
        $seed = \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        $seed = $e->getMessage();
    }
    
    return response()->json(['migrate' => $migrate, 'seed' => $seed]);
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('/posts', PostController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/videos', VideoController::class);
    Route::resource('/banners', App\Http\Controllers\BannerController::class);

    // Cart Routes
    Route::resource('/cart', App\Http\Controllers\CartController::class)->only(['index', 'store', 'update', 'destroy']);

    // Order Routes
    Route::get('/orders/my', [App\Http\Controllers\OrderController::class, 'myOrders'])->name('orders.my');
    Route::get('/orders/checkout', [App\Http\Controllers\OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/apply-coupon', [App\Http\Controllers\OrderController::class, 'applyCoupon'])->name('orders.applyCoupon');
    Route::get('/orders/direct-buy', [App\Http\Controllers\OrderController::class, 'directBuyPage'])->name('orders.directBuyPage');
    Route::post('/orders/direct-buy', [App\Http\Controllers\OrderController::class, 'storeDirectBuy'])->name('orders.directBuy');
    Route::resource('/orders', App\Http\Controllers\OrderController::class);
    Route::patch('/orders/{id}/status', [App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/orders/{id}/track', [App\Http\Controllers\OrderController::class, 'track'])->name('orders.track');
    Route::patch('/orders/{id}/shipping', [App\Http\Controllers\OrderController::class, 'updateShipping'])->name('orders.updateShipping');
    Route::get('/orders/{id}/courier-status', [App\Http\Controllers\OrderController::class, 'checkCourierStatus'])->name('orders.courierStatus');
    
    // Review & Comment Routes
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/comments', [App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');

    // Search Route
    Route::get('/search', [App\Http\Controllers\SearchController::class, 'index'])->name('search.index');

    // Wishlist Routes
    Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [App\Http\Controllers\WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Admin Dashboard
    Route::get('/admin/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('admin.dashboard');

    // POS System
    Route::get('/admin/pos', [App\Http\Controllers\Admin\POSController::class, 'index'])->name('admin.pos');
    Route::get('/admin/pos/search-products', [App\Http\Controllers\Admin\POSController::class, 'searchProducts'])->name('admin.pos.searchProducts');
    Route::get('/admin/pos/search-users', [App\Http\Controllers\Admin\POSController::class, 'searchUsers'])->name('admin.pos.searchUsers');
    Route::post('/admin/pos/checkout', [App\Http\Controllers\Admin\POSController::class, 'checkout'])->name('admin.pos.checkout');
    Route::get('/admin/pos/check-payment/{orderId}', [App\Http\Controllers\Admin\POSController::class, 'checkPaymentStatus'])->name('admin.pos.checkPayment');
    Route::get('/admin/pos/variants/{productId}', [App\Http\Controllers\Admin\POSController::class, 'getProductVariants'])->name('admin.pos.variants');
    Route::get('/admin/pos/transactions', [App\Http\Controllers\Admin\POSController::class, 'getRecentTransactions'])->name('admin.pos.transactions');
    Route::get('/admin/pos/transactions/{id}', [App\Http\Controllers\Admin\POSController::class, 'getTransactionDetail'])->name('admin.pos.transactionDetail');
    Route::get('/admin/pos/sales-report', [App\Http\Controllers\Admin\POSController::class, 'getSalesReport'])->name('admin.pos.salesReport');

    // User Addresses Profile
    Route::resource('/profile/addresses', App\Http\Controllers\AddressController::class);

    // Admin Payment Options Routes
    Route::resource('/admin/payment-options', App\Http\Controllers\Admin\PaymentOptionController::class)->names([
        'index' => 'admin.payment-options.index',
        'create' => 'admin.payment-options.create',
        'store' => 'admin.payment-options.store',
        'show' => 'admin.payment-options.show',
        'edit' => 'admin.payment-options.edit',
        'update' => 'admin.payment-options.update',
        'destroy' => 'admin.payment-options.destroy',
    ]);

    // Admin Coupon Routes
    Route::resource('/admin/coupons', App\Http\Controllers\Admin\CouponController::class)->names([
        'index' => 'admin.coupons.index',
        'create' => 'admin.coupons.create',
        'store' => 'admin.coupons.store',
        'show' => 'admin.coupons.show',
        'edit' => 'admin.coupons.edit',
        'update' => 'admin.coupons.update',
        'destroy' => 'admin.coupons.destroy',
    ]);

    // Admin Product Variant Routes
    Route::resource('/admin/products.variants', App\Http\Controllers\Admin\ProductVariantController::class)->names([
        'index' => 'admin.products.variants.index',
        'create' => 'admin.products.variants.create',
        'store' => 'admin.products.variants.store',
        'edit' => 'admin.products.variants.edit',
        'update' => 'admin.products.variants.update',
        'destroy' => 'admin.products.variants.destroy',
    ]);

    // Loyalty Points Routes
    Route::get('/loyalty-points', [App\Http\Controllers\LoyaltyPointController::class, 'index'])->name('loyalty-points.index');
    Route::post('/loyalty-points/redeem', [App\Http\Controllers\LoyaltyPointController::class, 'redeem'])->name('loyalty-points.redeem');

    // Buyer Payment Options Routes
    Route::get('/payment-options', [App\Http\Controllers\PaymentController::class, 'getPaymentOptions'])->name('payment.options.api');
    Route::get('/checkout/payment-options', [App\Http\Controllers\PaymentController::class, 'showPaymentOptions'])->name('payment.options.show');

    // Confessions
    Route::resource('/confessions', App\Http\Controllers\ConfessionController::class)->only(['index', 'store']);

    // AI Cash Detection
    Route::post('/admin/pos/cash-detection', [App\Http\Controllers\CashDetectionController::class, 'analyze'])->name('admin.pos.cashDetection');

    // Seller Routes (for users requesting to become sellers)
    Route::get('/seller', [App\Http\Controllers\SellerController::class, 'index'])->name('seller.index');
    Route::get('/seller/request', [App\Http\Controllers\SellerController::class, 'requestForm'])->name('seller.request');
    Route::post('/seller/request', [App\Http\Controllers\SellerController::class, 'submitRequest'])->name('seller.submitRequest');
    Route::get('/seller/products/create', [App\Http\Controllers\SellerController::class, 'createProduct'])->name('seller.products.create');
    Route::post('/seller/products', [App\Http\Controllers\SellerController::class, 'storeProduct'])->name('seller.products.store');

    // Admin Seller Management Routes
    Route::get('/admin/sellers/contracts', [App\Http\Controllers\Admin\SellerManagementController::class, 'contracts'])->name('admin.sellers.contracts');
    Route::get('/admin/sellers/contracts/{contract}', [App\Http\Controllers\Admin\SellerManagementController::class, 'showContract'])->name('admin.sellers.contracts.show');
    Route::post('/admin/sellers/contracts/{contract}/approve', [App\Http\Controllers\Admin\SellerManagementController::class, 'approveContract'])->name('admin.sellers.contracts.approve');
    Route::post('/admin/sellers/contracts/{contract}/reject', [App\Http\Controllers\Admin\SellerManagementController::class, 'rejectContract'])->name('admin.sellers.contracts.reject');
    Route::get('/admin/sellers/products', [App\Http\Controllers\Admin\SellerManagementController::class, 'products'])->name('admin.sellers.products');
    Route::get('/admin/sellers/products/{sellerProduct}', [App\Http\Controllers\Admin\SellerManagementController::class, 'showProduct'])->name('admin.sellers.products.show');
    Route::post('/admin/sellers/products/{sellerProduct}/approve', [App\Http\Controllers\Admin\SellerManagementController::class, 'approveProduct'])->name('admin.sellers.products.approve');
    Route::post('/admin/sellers/products/{sellerProduct}/reject', [App\Http\Controllers\Admin\SellerManagementController::class, 'rejectProduct'])->name('admin.sellers.products.reject');

    // Multi-Tenant Contract Management Routes
    Route::middleware(['tenant'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('contracts', App\Http\Controllers\Admin\ContractController::class)->except(['edit', 'update']);
        Route::get('contracts/{contract}/download', [App\Http\Controllers\Admin\ContractController::class, 'download'])->name('contracts.download');
    });
});

// Pakasir Payment Routes
Route::post('/payment/pakasir/create/{order}', [PaymentController::class, 'createPayment'])->name('payment.pakasir.create')->middleware('auth');
Route::get('/payment/pakasir/status/{order}', [PaymentController::class, 'checkStatus'])->name('payment.pakasir.status')->middleware('auth');
Route::get('/payment/pakasir/waiting/{order}', [PaymentController::class, 'showPaymentWaiting'])->name('payment.pakasir.waiting')->middleware('auth');
Route::get('/payment/pakasir/success', [PaymentController::class, 'paymentSuccess'])->name('payment.pakasir.success');
Route::get('/payment/pakasir/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.pakasir.cancel');
Route::post('/payment/pakasir/callback', [PaymentController::class, 'webhook'])->name('payment.pakasir.callback');
