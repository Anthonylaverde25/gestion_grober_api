<?php

namespace App\Http\Requests\V1\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class StartCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'machine_id' => 'required|uuid',
            'article_id' => 'required|uuid',
            'client_id' => 'required|uuid',
            'codigo' => 'nullable|string|max:50',
        ];
    }
}
