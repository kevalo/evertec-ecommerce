<?php

namespace App\Domain\Orders\Models;

use App\Support\Definitions\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
