<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    // Mass assignable fields
    protected $fillable = ['name'];

    /**
     * Get the features that have this status.
     */
    public function features()
    {
        return $this->hasMany(Feature::class);
    }
}
