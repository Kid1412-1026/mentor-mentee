<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'matric_no',
        'name',
        'program',
        'email',
        'intake',
        'phone',
        'mentor',
        'state',
        'address',
        'motto',
        'faculty',
        'img',
        'user_id'
    ];

    /**
     * Get the user that owns the student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student's result.
     */
    public function result(): HasOne
    {
        return $this->hasOne(Result::class);
    }

    /**
     * Get the student's activities.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the student's challenges.
     */
    public function challenges(): HasMany
    {
        return $this->hasMany(Challenge::class);
    }

    /**
     * Get the student's counseling sessions.
     */
    public function counselings(): HasMany
    {
        return $this->hasMany(Counseling::class);
    }

    /**
     * Get the student's course enrolments.
     */
    public function enrolments(): HasMany
    {
        return $this->hasMany(Enrolment::class);
    }

    /**
     * Get the student's KPI indexes.
     */
    public function kpiIndexes(): HasMany
    {
        return $this->hasMany(KpiIndex::class);
    }
}
