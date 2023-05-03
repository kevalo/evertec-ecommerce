<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'boolean'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }
}
