<?php

namespace App\Http\Requests\V1\LineYield;

use Illuminate\Foundation\Http\FormRequest;

class RecordLineYieldBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campaign_id' => 'required|uuid',
            'items' => 'required|array|min:1',
            'items.*.forming_yield' => 'required|numeric|min:0|max:100',
            'items.*.packing_yield' => 'required|numeric|min:0|max:100',
            'items.*.recorded_at' => 'nullable|date',
            'items.*.notes' => 'nullable|string',
            'items.*.user_alias_id' => 'nullable|uuid|exists:user_aliases,id',
        ];
    }
}
