<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'time_allotted',
        'project_id',
        'department_id',
        'subdepartment_id',
        'sort_order',
        'progress',
        'content',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime', // Cast deadline to a Carbon instance
    ];

    /**
     * A feature belongs to a project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * A feature belongs to a department.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * A feature optionally belongs to a subdepartment.
     */
    public function subdepartment(): BelongsTo
    {
        return $this->belongsTo(Subdepartment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
