<?php

namespace App\Domain\Payments\Models;

use App\Domain\Orders\Models\Order;
use App\Support\Definitions\PaymentStatus;
use Database\Factories\PaymentFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $request_id
 * @property string $process_url
 * @property string $payment_type
 * @property PaymentStatus $status
 * @property int $order_id
 */
class Payment extends Model
{
    use HasFactory;

    protected static function newFactory(): Factory
    {
        return PaymentFactory::new();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
