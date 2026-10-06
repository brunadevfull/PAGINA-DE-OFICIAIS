<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMilitaryPersonnelRequest extends FormRequest
{
    use AcceptsCamelCaseKeys;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'sometimes|string|max:255',
            'rank'           => 'sometimes|string|max:50',
            'type'           => 'sometimes|string|in:officer,master',
            'specialty'      => 'nullable|string|max:100',
            'full_rank_name' => 'sometimes|string|max:255',
            'active'         => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->mergeCamelCaseKeys(['fullRankName' => 'full_rank_name']);
    }
}
