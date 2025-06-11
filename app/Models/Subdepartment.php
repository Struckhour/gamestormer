<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Add this import

class Subdepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'name',
        'description',
    ];

    /**
     * A subdepartment belongs to a parent department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * A subdepartment can have many features.
     */
    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }
}
