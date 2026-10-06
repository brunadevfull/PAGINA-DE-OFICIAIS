<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pdf'      => 'required|file|mimetypes:application/pdf|max:102400',
            'title'    => 'required|string|max:500',
            'type'     => 'required|string|in:plasa,escala,cardapio,outros,bono',
            'category' => 'nullable|string|max:100',
            'unit'     => 'nullable|string|in:EAGM,1DN',
        ];
    }
}
