<?php

namespace App\Http\Requests\V1\Furnace;

use Illuminate\Foundation\Http\FormRequest;

class CreateFurnaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'glass_type_id' => 'required|integer',
            'max_capacity_tons' => 'required|numeric|min:0.1'
        ];
    }
}
