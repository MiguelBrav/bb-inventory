<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\StockMovementType;
use Illuminate\Validation\Rule;

class UpdateStockMovementRequest extends SpanishFormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => [
                'sometimes',
                'required',
                'integer',
                Rule::exists('products', 'id')->whereNull('deleted_at'),
            ],
            'type' => ['sometimes', 'required', Rule::in([
                StockMovementType::Entry->value,
                StockMovementType::Exit->value,
            ])],
            'quantity' => ['sometimes', 'required', 'integer', 'min:1', 'max:4294967295'],
            'reason' => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }
}
