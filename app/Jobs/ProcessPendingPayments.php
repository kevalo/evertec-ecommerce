<?php

namespace App\Jobs;

use App\Domain\Orders\Actions\UpdateOrderStatus;
use App\Domain\Payments\Actions\UpdatePaymentStatus;
use App\Support\Definitions\OrderStatus;
use App\Support\Definitions\PaymentStatus;
use App\Support\Services\PaymentFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPendingPayments implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle(PaymentFactory $paymentFactory): void
    {
        DB::transaction(static function () use ($paymentFactory) {
            $paymentInfo = DB::table('orders')
                ->select([
                    'orders.id as order_id',
                    'payments.id as payment_id',
                    'payments.status as payment_status',
                    'payments.request_id',
                    'payments.payment_type'
                ])
                ->join('payments', function (JoinClause $join) {
                    $join->on('orders.id', '=', 'payments.order_id')
                        ->whereIn('payments.status', [
                            PaymentStatus::CREATED->value,
                            PaymentStatus::PENDING->value,
                            PaymentStatus::APPROVED_PARTIAL->value,
                            PaymentStatus::PARTIAL_EXPIRED->value
                        ]);
                })
                ->where('orders.status', OrderStatus::CREATED->value)
                ->get();

            foreach ($paymentInfo as $info) {
                Log::debug("PAYMENT: " . $info->payment_id . "----Estado actual:" . $info->payment_status);

                $processor = $paymentFactory->initializePayment($info->payment_type);
                $status = $processor->getPaymentStatus((string)$info->request_id);
                UpdatePaymentStatus::execute(['id' => $info->payment_id, 'status' => $status]);

                if ($status === PaymentStatus::APPROVED->value) {
                    UpdateOrderStatus::execute(['id' => $info->order_id, 'status' => OrderStatus::COMPLETED->value]);
                }
            }
        });
    }
}
