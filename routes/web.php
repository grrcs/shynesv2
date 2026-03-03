<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController; // <--- Pastikan di-import
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VideoController;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    $products = \App\Models\Product::with('category')
        ->where('status', 'active')
        ->latest()
        ->take(3)
        ->get();
    return view('welcome', compact('products'));
})->name('welcome');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Temporary fix passwords route
Route::get('/fix-passwords', function () {
    $users = \App\Models\User::all();
    $fixed = 0;
    foreach ($users as $user) {
        // If password is not a bcrypt hash (doesn't start with $2y$)
        if (!str_starts_with($user->password, '$2y$')) {
            $user->password = bcrypt('password'); // Default password assumption or hash whatever was there
            // Actually, if we just want them to login with 'password', let's set it to bcrypt('password')
            $user->save();
            $fixed++;
        }
    }
    return "Fixed {$fixed} user passwords! You can now log in with 'password'.";
});

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
    Route::resource('/orders', App\Http\Controllers\OrderController::class);
    Route::patch('/orders/{id}/status', [App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
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

    // Confessions
    Route::resource('/confessions', App\Http\Controllers\ConfessionController::class)->only(['index', 'store']);
});
