<?php

namespace App\ViewModels\Admin\Product;

use App\Definitions\GeneralStatus;
use App\Models\Category;
use App\ViewModels\ViewModel;

class EditViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'product' => $this->model(),
            'categories' => Category::where('status', GeneralStatus::ACTIVE->value)->get()
        ];
    }
}
