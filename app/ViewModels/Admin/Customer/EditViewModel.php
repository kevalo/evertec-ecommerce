<?php

namespace App\ViewModels\Admin\Customer;

use App\ViewModels\ViewModel;

class EditViewModel extends ViewModel
{
     public function toArray(): array
    {
        return [
            'title' => 'Editar cliente',
            'customer' => $this->model
        ];
    }
}
