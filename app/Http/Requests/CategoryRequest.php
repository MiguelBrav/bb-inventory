<?php

declare(strict_types=1);

namespace App\Http\Requests;

class CategoryRequest extends SpanishFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
