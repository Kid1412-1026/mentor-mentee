<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiIndex extends Model
{
    protected $table = 'kpi_indexes';

    protected $fillable = [
        'cgpa',
        'faculty_activity',
        'university_activity',
        'national_activity',
        'international_activity',
        'faculty_competition',
        'university_competition',
        'national_competition',
        'international_competition',
        'leadership',
        'graduate_on_time',
        'professional_certification',
        'employability',
        'mobility_program',
        'sem',
        'year',
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
