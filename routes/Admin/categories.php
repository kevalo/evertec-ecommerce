<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
});
