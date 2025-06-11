<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    // The attributes that are mass assignable.
    protected $fillable = [
        'project_id',
        'feature_id',
        'file_name',
        'original_name',
        'mime_type',
        'path',
        'size',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    // Optional: Helper to get the full URL to the file
    public function getUrlAttribute()
    {
        return \Storage::url($this->path);
    }
}
