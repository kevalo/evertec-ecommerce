<?php

namespace App\Actions\Admin\Product;

use App\Actions\Action;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class UpdateProduct implements Action
{
    /**
     * @param array<string, array|Product> $params
     * @return bool
     */
    public static function execute(array $params): bool
    {
        $fields = $params['fields'];
        $product = $params['product'];

        if ($fields['image'] !== null) {
            Storage::disk('public')->delete($product->image);
            $fields['image'] = Storage::disk('public')->putFile('products_images', $fields['image']);
        } else {
            $fields['image'] = $product->image;
        }

        return $product->update($fields);
    }
}
