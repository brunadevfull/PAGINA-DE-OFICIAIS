<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoticeRequest extends FormRequest
{
    use AcceptsCamelCaseKeys;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => 'sometimes|string|max:500',
            'content'    => 'sometimes|string',
            'priority'   => 'sometimes|string|in:high,medium,low',
            'start_date' => 'sometimes|date',
            'end_date'   => 'sometimes|date',
            'active'     => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->mergeCamelCaseKeys([
            'startDate' => 'start_date',
            'endDate'   => 'end_date',
        ]);
    }
}
