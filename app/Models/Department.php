<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * A department can have many subdepartments.
     */
    public function subdepartments(): HasMany
    {
        return $this->hasMany(Subdepartment::class);
    }

    /**
     * A department can have many features.
     */
    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }
}
