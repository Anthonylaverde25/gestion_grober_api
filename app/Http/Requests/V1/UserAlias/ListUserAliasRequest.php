<?php

namespace App\Http\Requests\V1\UserAlias;

use Illuminate\Foundation\Http\FormRequest;

class ListUserAliasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id'
        ];
    }
}
