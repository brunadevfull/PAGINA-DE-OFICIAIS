<?php

namespace App\Models;

use App\Casts\PostgresArray;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';
    public $timestamps = false;

    protected $fillable = [
        'title', 'url', 'type', 'category', 'active', 'upload_date', 'tags', 'unit',
    ];

    protected $casts = [
        'active' => 'boolean',
        'upload_date' => 'datetime',
        'tags' => PostgresArray::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (Document $doc) {
            if (is_null($doc->upload_date)) {
                $doc->upload_date = now();
            }
        });
    }

    public function viewState(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DocumentViewState::class, 'document_id', 'id');
    }
}
