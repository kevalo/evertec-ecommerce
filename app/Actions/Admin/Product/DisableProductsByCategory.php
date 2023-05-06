<?php

namespace App\Actions\Admin\Product;

use App\Actions\Action;
use App\Definitions\GeneralStatus;
use App\Models\Product;

class DisableProductsByCategory implements Action
{
    public static function execute(array $params): bool
    {
        return Product::where('category_id', $params['category_id'])->update(
            ['status' => GeneralStatus::INACTIVE->value]
        );
    }
}
