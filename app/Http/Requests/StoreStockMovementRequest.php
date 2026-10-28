<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\StockMovementType;
use Illuminate\Validation\Rule;

class StoreStockMovementRequest extends SpanishFormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->whereNull('deleted_at'),
            ],
            'type' => ['required', Rule::in([
                StockMovementType::Entry->value,
                StockMovementType::Exit->value,
            ])],
            'quantity' => ['required', 'integer', 'min:1', 'max:4294967295'],
            'reason' => ['required', 'string', 'max:255'],
        ];
    }
}
