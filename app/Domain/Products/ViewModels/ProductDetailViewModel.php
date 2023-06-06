<?php

namespace App\Domain\Products\ViewModels;

use App\Domain\Categories\Models\Category;
use App\Support\ViewModels\ViewModel;

class ProductDetailViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'product' => $this->model,
            'category' => Category::find($this->model->category_id)
        ];
    }
}
