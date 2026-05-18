<?php

namespace App\Http\Requests\V1\UserAlias;

use Illuminate\Foundation\Http\FormRequest;

class SearchUserAliasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'legajo' => 'required|string|max:50'
        ];
    }
}
