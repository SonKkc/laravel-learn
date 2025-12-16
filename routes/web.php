
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;


// Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// My Orders
Route::get('/my-order', [OrderController::class, 'myOrders'])->name('orders.my');
Route::get('/my-order/{order}', [OrderController::class, 'show'])->name('orders.show');

// Auth
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::get('/forgot', [AuthController::class, 'forgotForm'])->name('forgot');
Route::get('/reset', [AuthController::class, 'resetForm'])->name('reset');

// Payment result
Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

// User Profile
Route::get('/profile', function () {
    return view('profile.show');
})->middleware('auth')->name('profile.show');
