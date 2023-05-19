<?php

namespace App\Http\Requests\Web\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:255'],
            'image' => ['nullable','image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'price' => ['required', 'numeric', 'min:1000'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }
}
