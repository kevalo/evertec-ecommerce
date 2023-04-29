<?php

namespace App\ViewModels\Admin\Product;

use App\ViewModels\ViewModel;

class EditViewModel extends ViewModel
{
     public function toArray(): array
    {
        return [
            'title' => 'Editar producto',
            'category' => $this->model
        ];
    }
}
