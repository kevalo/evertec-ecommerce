<?php

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');

    Route::patch('/toggleCustomerStatus', [CustomerController::class, 'toggleStatus'])
        ->name('toggleCustomerStatus');

    Route::get('/customers/{user}', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{user}', [CustomerController::class, 'update'])->name('customers.update');
});
