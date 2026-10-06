<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DutyAssignment extends Model
{
    protected $table = 'duty_assignments';

    const CREATED_AT = null;
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'officer_name', 'officer_rank', 'master_name', 'master_rank', 'valid_from',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
