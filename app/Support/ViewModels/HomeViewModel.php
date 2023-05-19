<?php

namespace App\Support\ViewModels;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Support\Definitions\GeneralStatus;
use Illuminate\Support\Facades\Route;

class HomeViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Product());
    }

    public function toArray(): array
    {
        return [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'categories' => Category::where('status', GeneralStatus::ACTIVE->value)->get()
        ];
    }
}
