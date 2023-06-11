<?php

namespace App\Domain\Orders\Models;

use App\Domain\Payments\Models\Payment;
use App\Domain\Products\Models\Product;
use App\Domain\Users\Models\User;
use App\Support\Definitions\OrderStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ramsey\Collection\Collection;

/**
 * @property string $code
 * @property OrderStatus|null|string $status
 * @property float $total_price
 * @property string $delivery_address
 * @property int $user_id
 * @property Collection<Product> $products
 */
class Order extends Model
{
    use HasFactory;

    protected static function newFactory(): Factory
    {
        return OrderFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')
            ->select('products.id', 'name', 'image', 'slug')
            ->withPivot(['price', 'quantity', 'total']);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }
}
