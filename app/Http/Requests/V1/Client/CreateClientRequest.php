<?php

namespace App\Http\Requests\V1\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'commercial_name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:50',
            'technical_contact' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ];
    }
}
