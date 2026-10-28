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
            'confirmed' => __('validation.confirmed', [], 'es'),
            'date' => __('validation.date', [], 'es'),
            'email' => __('validation.email', [], 'es'),
            'exists' => __('validation.exists', [], 'es'),
            'in' => __('validation.in', [], 'es'),
            'integer' => __('validation.integer', [], 'es'),
            'lowercase' => __('validation.lowercase', [], 'es'),
            'max' => __('validation.max', [], 'es'),
            'min' => __('validation.min', [], 'es'),
            'numeric' => __('validation.numeric', [], 'es'),
            'required' => __('validation.required', [], 'es'),
            'string' => __('validation.string', [], 'es'),
            'unique' => __('validation.unique', [], 'es'),
        ];
    }

    public function attributes(): array
    {
        return __('validation.attributes', [], 'es');
    }
}
