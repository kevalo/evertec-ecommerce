<?php

namespace App\Http\Requests\Web\Payment;

use App\Support\Definitions\PaymentMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int|In|string>>
     */
    public function rules(): array
    {
        return [
            'payment_type' => [
                'required',
                Rule::in(PaymentMethods::toArray()),
            ],
            'order_id' => ['required', 'numeric', 'exists:orders,id']
        ];
    }
}
