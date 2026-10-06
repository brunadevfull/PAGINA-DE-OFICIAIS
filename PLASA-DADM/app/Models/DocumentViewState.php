<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentViewState extends Model
{
    protected $table = 'document_view_states';

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'document_id', 'zoom', 'scroll_top', 'scroll_left',
    ];
}
