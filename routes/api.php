<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController as UserApiProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/sanctum/token', [UserApiProductController::class, 'getToken']);

Route::get('/products', [UserApiProductController::class, 'index'])->name('.products');
Route::post('/products/cart', [UserApiProductController::class, 'getProductsForCart'])->name('.getCartProducts');

Route::post('/products/check-stock', [UserApiProductController::class, 'checkStock'])
    ->name('.products.checkStock');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('.user');

    Route::name('.customers')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::patch('/customers/toggle-status', [CustomerController::class, 'toggleStatus'])->name('.toggleStatus');
    });

    Route::name('.categories')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index']);
        Route::patch('/categories/toggle-status', [CategoryController::class, 'toggleStatus'])->name('.toggleStatus');
    });

    Route::name('.admin.products')->group(function () {
        Route::get('/products/list', [ProductController::class, 'index']);
        Route::patch('/products/toggle-status', [ProductController::class, 'toggleStatus'])->name('.toggleStatus');
    });

    Route::post('/products', [UserApiProductController::class, 'store'])->name('.products.store');
    Route::get('/products/{id}', [UserApiProductController::class, 'show'])->name('.products.show');
    Route::put('/products/{id}', [UserApiProductController::class, 'update'])->name('.products.update');

    Route::name('.orders')->group(function () {
        Route::post('/orders', [OrderController::class, 'store'])->name('.store');
        Route::get('/orders/list', [AdminOrderController::class, 'index'])->name('.index');
    });
});
