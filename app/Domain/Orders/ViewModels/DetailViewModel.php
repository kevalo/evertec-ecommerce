<?php

namespace App\Domain\Orders\ViewModels;

use App\Support\Definitions\OrderStatus;
use App\Support\ViewModels\ViewModel;

class DetailViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'order' => $this->model,
            'products' =>$this->model->products,
            'status' => OrderStatus::toArray()
        ];
    }
}
