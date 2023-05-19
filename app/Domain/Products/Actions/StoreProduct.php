<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Models\Product;
use App\Support\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreProduct implements Action
{
    /**
     * @param array $params
     * @return bool
     */
    public static function execute(array $params): bool
    {
        $product = new Product();
        $product->name = $params['name'];
        $product->description = $params['description'];
        $product->image = Storage::disk('public')->putFile('products_images', $params['image']);
        $product->slug = Str::slug($params['name'], '-', 'es');
        $product->price = $params['price'];
        $product->quantity = $params['quantity'];
        $product->category_id = $params['category_id'];
        $product->status = $params['status'];

        return $product->save();
    }
}
