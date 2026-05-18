<?php

namespace App\Http\Requests\V1\UserAlias;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserAliasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'legajo' => 'required|string|max:50|unique:user_aliases,legajo'
        ];
    }
}
