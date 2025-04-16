<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'code',
        'name',
        'credit_hour',
        'section',
        'faculty'
    ];

    public function enrolments(): HasMany
    {
        return $this->hasMany(Enrolment::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class);
    }
}
