<?php

namespace App\ViewModels\Admin\Category;

use App\Models\Category;
use App\ViewModels\ViewModel;

class CreateViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Category());
    }

    public function toArray(): array
    {
        return ['title' => 'Crear categoría'];
    }
}
