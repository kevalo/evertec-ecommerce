<?php

namespace App\Support\ViewModels;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

abstract class ViewModel implements Arrayable
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    abstract public function toArray(): array;

    protected function model(): Model
    {
        return $this->model;
    }
}
