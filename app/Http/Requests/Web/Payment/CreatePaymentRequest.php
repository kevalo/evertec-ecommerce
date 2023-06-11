<?php

namespace App\Http\Requests\Web\Payment;

use App\Support\Definitions\PaymentMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, string>
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
