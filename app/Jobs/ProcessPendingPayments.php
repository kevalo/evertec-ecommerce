<?php

namespace App\Jobs;

use App\Domain\Orders\Actions\UpdateOrderStatus;
use App\Domain\Payments\Actions\UpdatePaymentStatus;
use App\Support\Definitions\OrderStatus;
use App\Support\Definitions\PaymentStatus;
use App\Support\Services\PaymentFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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

    /**
     * Execute the job.
     */
    public function handle(PaymentFactory $paymentFactory): void
    {
        DB::transaction(static function () use ($paymentFactory) {
            $orders = DB::table('orders')
                ->where('status', OrderStatus::CREATED->value)
                ->get();

            foreach ($orders as $order) {
                $payment = DB::table('payments')
                    ->select('id', 'request_id', 'status', 'payment_type')
                    ->whereIn('status', [
                        PaymentStatus::CREATED->value,
                        PaymentStatus::PENDING->value,
                        PaymentStatus::APPROVED_PARTIAL->value,
                        PaymentStatus::PARTIAL_EXPIRED->value,
                    ])
                    ->where('order_id', $order->id)->first();

                Log::debug("PAYMENT: ".$payment->id."----Estado actual:".$payment->status);

                $processor = $paymentFactory->initializePayment($payment->payment_type);
                $status = $processor->getPaymentStatus((string)$payment->request_id);
                UpdatePaymentStatus::execute(['id' => $payment->id, 'status' => $status]);

                if ($status === PaymentStatus::APPROVED->value) {
                    UpdateOrderStatus::execute(['id' => $order->id, 'status' => OrderStatus::COMPLETED->value]);
                }
            }
        });
    }
}
