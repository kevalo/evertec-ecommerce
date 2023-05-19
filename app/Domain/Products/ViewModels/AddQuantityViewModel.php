<?php

namespace App\Domain\Products\ViewModels;

use App\Support\ViewModels\ViewModel;

class AddQuantityViewModel extends ViewModel
{
    public function toArray(): array
    {
        return [
            'product' => $this->model()
        ];
    }
}
