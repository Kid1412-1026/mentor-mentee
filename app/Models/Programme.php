<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model
{
    protected $fillable = [
        'code',
        'name'
    ];

    public function rules(): HasMany
    {
        return $this->hasMany(Rule::class);
    }
}
