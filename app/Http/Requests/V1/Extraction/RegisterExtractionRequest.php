<?php

namespace App\Http\Requests\V1\Extraction;

use Illuminate\Foundation\Http\FormRequest;

class RegisterExtractionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'machine_id' => 'required|uuid|exists:machines,id',
            'article_id' => 'required|uuid|exists:articles,id',
            'percentage' => 'required|numeric|min:0|max:100',
            'measured_at' => 'nullable|date',
        ];
    }
}
