<?php

namespace App\ViewModels;

use App\Definitions\GeneralStatus;
use App\Models\Category;
use App\Models\Product;
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
