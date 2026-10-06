<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMilitaryPersonnelRequest extends FormRequest
{
    use AcceptsCamelCaseKeys;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'rank'           => 'required|string|max:50',
            'type'           => 'required|string|in:officer,master',
            'specialty'      => 'nullable|string|max:100',
            'full_rank_name' => 'required|string|max:255',
            'active'         => 'boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->mergeCamelCaseKeys(['fullRankName' => 'full_rank_name']);
    }
}
