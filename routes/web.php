<?php

use App\Http\Controllers\Web\Admin\CategoryController;
use App\Http\Controllers\Web\Admin\CustomerController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\Admin\ProductController;
use App\Http\Controllers\Web\Admin\ProductQuantityController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductController as HomeProductController;
use App\Http\Controllers\Web\ProfileController;
use App\Support\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products/detail/{slug}', [HomeProductController::class, 'show'])->name('product-detail');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customers/{user}', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{user}', [CustomerController::class, 'update'])->name('customers.update');

    Route::resource('categories', CategoryController::class)->except(['destroy']);

    Route::resource('products', ProductController::class)->except(['destroy']);
    Route::get('products/{product}/add', [ProductQuantityController::class, 'show'])->name('products.add');
    Route::patch('products/{product}/add', [ProductQuantityController::class, 'update'])->name('products.add');
});

require __DIR__.'/auth.php';
