<?php

namespace App\Http\Requests\Customer;


use Illuminate\Foundation\Http\FormRequest;

class ToogleStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'numeric',
                'exists:users'
            ]
        ];
    }
}
