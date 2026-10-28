<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UpdateUserRequest extends SpanishFormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');

        return $this->user()?->is_admin === true
            && $user instanceof User
            && ! $user->is_admin;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
