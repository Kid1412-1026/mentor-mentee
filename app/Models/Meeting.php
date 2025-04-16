<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    protected $fillable = [
        'sem',
        'year',
        'batch',
        'session_date',
        'method',
        'duration',
        'agenda',
        'discussion',
        'action',
        'remarks',
        'admin_id'
    ];

    protected $casts = [
        'session_date' => 'date'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}