<?php

namespace App\ViewModels\Admin\Category;

use App\Models\Category;
use App\ViewModels\ViewModel;

class ListViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Category());
    }

    public function toArray(): array
    {
        return ['title' => 'Categorías'];
    }
}
