<?php

namespace App\Http\Requests\Web\Product;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
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
            'file' => ['required', 'file', 'mimetypes:text/csv']
        ];
    }
}
