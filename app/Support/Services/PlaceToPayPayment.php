<?php

namespace App\Support\Services;

use App\Contracts\PaymentInterface;
use App\Domain\Orders\Models\Order;
use App\Http\Requests\Web\Payment\CreatePaymentRequest;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Http;

class PlaceToPayPayment extends PaymentInterface
{
    private array $fields;

    public function setUpAuth(): array
    {
        $currentDate = new DateTime();
        $nonce = (string)time();
        // Returns ISO8601 in proper format
        $seed = $currentDate->format('c');

        return [
            "login" => config('services.placetopay.login'),
            "tranKey" => base64_encode(
                hash(
                    'sha256',
                    $nonce . $seed . config('services.placetopay.secret_key'),
                    true
                )
            ),
            "nonce" => base64_encode($nonce),
            "seed" => $seed
        ];
    }

    /**
     * @throws Exception
     */
    public function pay(): bool|array
    {
        $response = Http::post(config('services.placetopay.baseUrl') . '/session', $this->fields);
        $jsonResponse = $response->json();
        if ($jsonResponse['status']['status'] === 'OK') {
            return $jsonResponse;
        }
        return false;
    }

    /**
     * @throws Exception
     */
    public function setUpPayment(CreatePaymentRequest $request): static
    {
        $currentDate = new DateTime();

        $expires = $currentDate
            ->add(new DateInterval('PT' . config('services.placetopay.timeout') . 'M'))
            ->format('c');

        $order = Order::find($request->validated('order_id'));

        $this->fields = [
            "locale" => "es_CO",
            "auth" => $this->setUpAuth(),
            "payment" => [
                "reference" => $order->code . '_' . $order->id,
                "description" => "Pago desde " . config('app.name'),
                "amount" => [
                    "currency" => "COP",
                    "total" => $order->total_price
                ]
            ],
            "expiration" => $expires,
            "returnUrl" => route('orders.show', $order->id),
            "ipAddress" => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            "userAgent" => "PlacetoPay Sandbox"
        ];

        return $this;
    }

    public function getPaymentStatus(string $requestId): string
    {
        $response = Http::post(
            config('services.placetopay.baseUrl') . "/session/$requestId",
            ['auth' => $this->setUpAuth()]
        );

        return $response->json()['status']['status'];
    }
}
