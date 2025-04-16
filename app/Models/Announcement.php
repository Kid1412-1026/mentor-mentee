<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file',
        'admin_id'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}

