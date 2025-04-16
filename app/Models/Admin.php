<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'faculty',
        'pose',
        'img',
        'user_id'
    ];

    /**
     * Get the user that owns the admin.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
