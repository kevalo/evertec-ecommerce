<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\CustomerController;
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

// All the routes in this file have the name prefix: api

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
})->name('.user');

Route::name('.customers')->group(function () {
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('.show');
    Route::patch('/customers/toggle-status', [CustomerController::class, 'toggleStatus'])->name('.toggleStatus');
});

Route::name('.categories')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::patch('/categories/toggle-status', [CategoryController::class, 'toggleStatus'])->name('.toggleStatus');
});
