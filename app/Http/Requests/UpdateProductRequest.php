<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateProductRequest extends SpanishFormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->whereNull('deleted_at'),
            ],
            'sku' => ['sometimes', 'required', 'string', 'max:255'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'min_stock' => ['sometimes', 'required', 'integer', 'min:0', 'max:4294967295'],
            'cost_price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'sale_price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'expiration_date' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
