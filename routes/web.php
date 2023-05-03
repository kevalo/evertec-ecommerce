<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customers/{user}', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{user}', [CustomerController::class, 'update'])->name('customers.update');

    Route::resource('categories', CategoryController::class)->except(['destroy']);

    Route::resource('products', ProductController::class)->except(['destroy']);
    Route::get('products/{product}/add', [ProductController::class, 'showAddQuantity'])->name('products.add');
    Route::patch('products/{product}/add', [ProductController::class, 'addQuantity'])->name('products.add');
});

require __DIR__.'/auth.php';
