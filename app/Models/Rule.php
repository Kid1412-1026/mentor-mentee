<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rule extends Model
{
    protected $fillable = [
        'course_id',
        'programme_id',
        'intake'
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }
}