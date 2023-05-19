<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Models\Product;
use App\Support\Actions\Action;
use App\Support\Definitions\GeneralStatus;

class DisableProductsByCategory implements Action
{
    public static function execute(array $params): bool
    {
        return Product::where('category_id', $params['category_id'])->update(
            ['status' => GeneralStatus::INACTIVE->value]
        ) > 0;
    }
}
