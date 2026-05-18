<?php

namespace App\Http\Requests\V1\Machine;

use Illuminate\Foundation\Http\FormRequest;

class ListMachinesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required_without:furnace_id|nullable|uuid|exists:companies,id',
            'furnace_id' => 'required_without:company_id|nullable|uuid|exists:furnaces,id',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required_without' => 'Debe enviar company_id o furnace_id',
            'furnace_id.required_without' => 'Debe enviar company_id o furnace_id',
        ];
    }
}
