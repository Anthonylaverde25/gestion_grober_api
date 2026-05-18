<?php

namespace App\Http\Requests\V1\Machine;

use Illuminate\Foundation\Http\FormRequest;

class ChangeMachineArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'article_id' => 'nullable|uuid|exists:articles,id',
        ];
    }
}
