<?php

namespace App\ViewModels\Admin\Product;

use App\Models\Product;
use App\ViewModels\ViewModel;

class CreateViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Product());
    }

    public function toArray(): array
    {
        return ['title' => 'Crear producto'];
    }
}
