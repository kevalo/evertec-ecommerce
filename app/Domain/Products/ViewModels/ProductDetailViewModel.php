<?php

namespace App\Domain\Products\ViewModels;

use App\Domain\Categories\Models\Category;
use App\Domain\Products\Models\Product;
use App\Support\ViewModels\ViewModel;

class ProductDetailViewModel extends ViewModel
{
    public function toArray(): array
    {
        /**
         * @var Product $model
         */
        $model = $this->model;
        return [
            'product' => $model,
            'category' => Category::find($model->category_id)
        ];
    }
}
