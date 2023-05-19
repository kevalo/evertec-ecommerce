<?php

namespace App\Domain\Products\ViewModels;

use App\Domain\Categories\Models\Category;
use App\Support\Definitions\GeneralStatus;
use App\Support\ViewModels\ViewModel;

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
