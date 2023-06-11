<?php

namespace App\Support\Services;

use App\Contracts\PaymentInterface;
use App\Http\Requests\Web\Payment\CreatePaymentRequest;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Http;

class PlaceToPayPayment implements PaymentInterface
{
    /**
     * @throws \Exception
     */
    public function pay(CreatePaymentRequest $request): bool|array
    {
        $currentDate = new DateTime();
        $nonce = (string)time();
        // Returns ISO8601 in proper format
        $seed = $currentDate->format('c');
        $authData = [
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

        $expires = $currentDate
            ->add(new DateInterval('PT' . config('services.placetopay.timeout') . 'M'))
            ->format('c');


        $fields = [
            "locale" => "es_CO",
            "auth" => $authData,
            "payment" => [
                "reference" => $request->validated('order_id'),
                "description" => "Pago desde " . config('app.name'),
                "amount" => [
                    "currency" => "COP",
                    "total" => 1000
                ]
            ],
            "expiration" => $expires,
            "returnUrl" => route('orders.show', $request->validated('order_id')),
            "ipAddress" => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            "userAgent" => "PlacetoPay Sandbox"
        ];

        $response = Http::post(config('services.placetopay.baseUrl') . '/session', $fields);
        $jsonResponse = $response->json();
        if ($jsonResponse['status']['status'] === 'OK') {
            return $jsonResponse;
        }
        return false;
    }
}
