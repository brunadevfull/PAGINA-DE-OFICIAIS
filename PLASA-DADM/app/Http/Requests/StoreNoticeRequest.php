<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoticeRequest extends FormRequest
{
    use AcceptsCamelCaseKeys;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => 'required|string|max:500',
            'content'    => 'required|string',
            'priority'   => 'required|string|in:high,medium,low',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
            'active'     => 'boolean',
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
