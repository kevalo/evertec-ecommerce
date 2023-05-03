<?php

namespace App\ViewModels\Admin\Product;

use App\Definitions\GeneralStatus;
use App\Models\Category;
use App\ViewModels\ViewModel;

class AddQuantityViewModel extends ViewModel
{
     public function toArray(): array
    {
        return [
            'title' => 'Agregar unidades del producto',
            'product' => $this->model
        ];
    }
}
