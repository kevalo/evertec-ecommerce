<?php

namespace App\ViewModels\Admin\Customer;

use App\Models\User;
use App\ViewModels\ViewModel;

class ListViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    public function toArray(): array
    {
        return ['title' => 'Clientes'];
    }
}
