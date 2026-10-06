<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDutyOfficerRequest extends FormRequest
{
    use AcceptsCamelCaseKeys;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'officer_name' => 'required|string|max:255',
            'officer_rank' => 'nullable|string|max:50',
            'master_name'  => 'required|string|max:255',
            'master_rank'  => 'nullable|string|max:50',
            'valid_from'   => 'nullable|date',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->mergeCamelCaseKeys([
            'officerName' => 'officer_name',
            'officerRank' => 'officer_rank',
            'masterName'  => 'master_name',
            'masterRank'  => 'master_rank',
            'validFrom'   => 'valid_from',
        ]);
    }
}
