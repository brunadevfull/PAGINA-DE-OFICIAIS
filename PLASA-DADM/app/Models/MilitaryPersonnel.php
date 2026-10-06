<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilitaryPersonnel extends Model
{
    protected $table = 'military_personnel';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'name', 'rank', 'type', 'specialty', 'full_rank_name', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
