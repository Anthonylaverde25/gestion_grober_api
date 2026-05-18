<?php

namespace App\Http\Requests\V1\Machine;

use Illuminate\Foundation\Http\FormRequest;

class CreateMachineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|uuid|exists:companies,id',
            'furnace_id' => 'required|uuid|exists:furnaces,id',
            'current_article_id' => 'nullable|uuid|exists:articles,id',
            'name' => 'required|string|max:100',
            'status' => 'nullable|string|in:operational,maintenance,shutdown',
        ];
    }
}
