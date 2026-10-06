<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'    => 'required|string|max:500',
            'url'      => 'required|string|max:1000',
            'type'     => 'required|string|in:plasa,escala,cardapio,outros,bono,aviso',
            'category' => 'nullable|string|max:100',
            'active'   => 'boolean',
            'tags'     => 'nullable|array',
            'tags.*'   => 'string|max:50',
            'unit'     => 'nullable|string|in:EAGM,1DN',
        ];
    }
}
