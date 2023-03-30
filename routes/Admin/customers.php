<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Customer\CustomerController;

Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::patch('/toggleCustomerStatus', [CustomerController::class, 'toggleStatus'])
        ->name('toggleCustomerStatus');
});