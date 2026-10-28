<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class SpanishFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'date' => __('validation.date', [], 'es'),
            'exists' => __('validation.exists', [], 'es'),
            'in' => __('validation.in', [], 'es'),
            'integer' => __('validation.integer', [], 'es'),
            'max' => __('validation.max', [], 'es'),
            'min' => __('validation.min', [], 'es'),
            'numeric' => __('validation.numeric', [], 'es'),
            'required' => __('validation.required', [], 'es'),
            'string' => __('validation.string', [], 'es'),
        ];
    }

    public function attributes(): array
    {
        return __('validation.attributes', [], 'es');
    }
}
