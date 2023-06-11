<?php

namespace App\Domain\Products\ViewModels;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Support\Definitions\GeneralStatus;

class CreateViewModel extends \App\Support\ViewModels\ViewModel
{
    public function __construct()
    {
        parent::__construct(new Product());
    }

    public function toArray(): array
    {
        return [
            'categories' => Category::where('status', GeneralStatus::ACTIVE->value)->get()
        ];
    }
}
