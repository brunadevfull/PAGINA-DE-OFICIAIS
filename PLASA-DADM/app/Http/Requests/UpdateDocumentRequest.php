<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'    => 'sometimes|string|max:500',
            'url'      => 'sometimes|string|max:1000',
            'type'     => 'sometimes|string|in:plasa,escala,cardapio,outros,bono,aviso',
            'category' => 'nullable|string|max:100',
            'active'   => 'sometimes|boolean',
            'tags'     => 'nullable|array',
            'tags.*'   => 'string|max:50',
            'unit'     => 'nullable|string|in:EAGM,1DN',
        ];
    }
}
