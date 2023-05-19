<?php

namespace App\ViewModels\Admin\Product;

use App\ViewModels\ViewModel;

class AddQuantityViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'product' => $this->model()
        ];
    }
}
