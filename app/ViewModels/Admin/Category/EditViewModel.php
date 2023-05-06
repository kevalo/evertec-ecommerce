<?php

namespace App\ViewModels\Admin\Category;

use App\ViewModels\ViewModel;

class EditViewModel extends ViewModel
{
     public function toArray(): array
    {
        return [
            'title' => 'Editar categoría',
            'category' => $this->model
        ];
    }
}
