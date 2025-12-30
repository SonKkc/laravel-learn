<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderCancellationRequestController;
use App\Http\Controllers\Admin\OrderCancellationController;


// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Categories
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/preview', [CartController::class, 'preview'])->name('cart.preview');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/json', [CartController::class, 'json'])->name('cart.json');

// Checkout
Route::middleware('auth')->group(function () {
	Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
	Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
	// Address book for users
	Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
	Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
	Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
	Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
});

// My Orders
Route::middleware('auth')->group(function () {
	Route::get('/my-order', [OrderController::class, 'myOrders'])->name('orders.my');
	Route::get('/my-order/{order}', [OrderController::class, 'show'])->name('orders.show');
	// User: submit an order cancellation request
	Route::post('/my-order/{order}/cancellation-requests', [OrderCancellationRequestController::class, 'store'])->name('orders.cancellations.store');
});

// Auth
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/forgot', [AuthController::class, 'forgotForm'])->name('forgot');
Route::post('/forgot', [AuthController::class, 'forgot'])->name('forgot.post');

Route::get('/reset', [AuthController::class, 'resetForm'])->name('reset');
Route::post('/reset', [AuthController::class, 'reset'])->name('reset.post');

// Payment result
Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

// User Profile

Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth')->name('profile.show');
Route::put('/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');
