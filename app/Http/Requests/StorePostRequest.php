<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'min: 5',
            ],
            'body' => [
                'required',
                'min: 5',
            ],
            'tags' => [
                'string',  // todo: custom rule
            ],
        ];
    }
}
