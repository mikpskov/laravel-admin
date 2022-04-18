<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property User $user
 */
final class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'min:3',
            ],
            'email' => [
                'email',
                Rule::unique(User::class)->ignore($this->user),
            ],
            'role' => [
                'nullable',
                'integer',
            ],
            'permissions' => [
                'array',
            ],
            'permissions.*' => [
                'required',
                'integer',
            ],
        ];
    }
}
