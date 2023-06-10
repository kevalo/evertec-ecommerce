<?php

namespace App\Http\Requests\Api\Order;

use App\Support\Definitions\Roles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role_id === Roles::CUSTOMER->value;
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'products' => ['required', 'array'],
            'products.*' => ['required', 'array:id,amount'],
            'products.*.id' => ['required', 'exists:products,id']
        ];
    }
}
