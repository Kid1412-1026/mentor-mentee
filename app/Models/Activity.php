<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'sem',
        'year',
        'name',
        'type',
        'remark',
        'uploads',
        'student_id'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}