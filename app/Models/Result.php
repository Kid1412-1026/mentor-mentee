<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $fillable = [
        'cgpa',
        'faculty_activity',
        'university_activity',
        'national_activity',
        'interaction',
        'faculty_competition',
        'university_competition',
        'national_competition',
        'interaction_competition',
        'leadership_competition',
        'graduate_on_time',
        'professional_certification',
        'employability',
        'mobility_program',
        'student_id'
    ];

    protected $casts = [
        'cgpa' => 'decimal:2'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}