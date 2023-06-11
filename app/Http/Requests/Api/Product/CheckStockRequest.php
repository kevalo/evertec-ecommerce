<?php

namespace App\Http\Requests\Api\Product;

use Illuminate\Foundation\Http\FormRequest;

class CheckStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric', 'exists:products'],
            'amount' => ['required', 'numeric', 'gt:0']
        ];
    }
}
