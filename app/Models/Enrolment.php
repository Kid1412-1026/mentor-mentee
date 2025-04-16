<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrolment extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'sem',
        'year',
        'pointer',
        'grade',
        'rating'
    ];

    protected $casts = [
        'pointer' => 'decimal:2'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}