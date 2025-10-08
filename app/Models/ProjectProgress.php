<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'developer_id',
        'work_date',
        'hours_worked',
        'description',
        'progress_percentage',
        'tasks_completed',
        'challenges_faced',
        'next_steps',
        'status',
    ];

    protected $casts = [
        'work_date' => 'date',
        'hours_worked' => 'decimal:2',
        'tasks_completed' => 'array',
        'challenges_faced' => 'array',
        'next_steps' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class, 'progress_id');
    }

    public function getFormattedHoursAttribute(): string
    {
        return number_format($this->hours_worked, 2) . ' hours';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'green',
            'in_progress' => 'blue',
            'blocked' => 'red',
            'on_hold' => 'yellow',
            default => 'gray'
        };
    }
}