<?php

namespace App\Http\Requests\V1\LineYield;

use Illuminate\Foundation\Http\FormRequest;

class RecordLineYieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign_id' => 'required|uuid',
            'forming_yield' => 'required|numeric|min:0|max:100',
            'packing_yield' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'user_alias_id' => 'nullable|uuid|exists:user_aliases,id',
        ];
    }
}
