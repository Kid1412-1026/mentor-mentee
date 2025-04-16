<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiGoal extends Model
{
    public $timestamps = false;
    
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
        'admin_id'
    ];

    protected $casts = [
        'cgpa' => 'decimal:2'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}