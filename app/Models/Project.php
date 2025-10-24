<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProjectDocument;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'topic',
        'status',
        'start_date',
        'end_date',
        'delivery_date',
        'day_budget',
        'total_days',
        'days_used',
        'weekend_days',
        'estimated_hours',
        'notes',
        'progress',

        // status meta
        'pending_note',
        'due_date',
        'completed_at',
        'cancel_reason',

        // Enhanced fields
        'assigned_developer_id',
        'assigned_administrator_id',
        'client_requirements',
        'administrator_notes',
        'developer_notes',
        'priority',
        'assigned_at',
        'started_at',
        'last_activity_at',
    ];

    protected $casts = [
        'start_date'    => 'date',
        'end_date'      => 'date',
        'delivery_date' => 'date',
        'due_date'      => 'date',
        'completed_at'  => 'date',
        'total_days'    => 'integer',
        'days_used'     => 'integer',
        'weekend_days'  => 'integer',
        'estimated_hours' => 'decimal:2',
        'progress'      => 'integer',
        'assigned_at'   => 'datetime',
        'started_at'    => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    // Enhanced relationships
    public function assignedDeveloper()
    {
        return $this->belongsTo(User::class, 'assigned_developer_id');
    }

    public function assignedAdministrator()
    {
        return $this->belongsTo(User::class, 'assigned_administrator_id');
    }

    public function requirements()
    {
        return $this->hasMany(ProjectRequirement::class);
    }

    public function activities()
    {
        return $this->hasMany(ProjectActivity::class);
    }

    public function timeLogs()
    {
        return $this->hasMany(ProjectTimeLog::class);
    }

    // New progress tracking relationships
    public function progressUpdates()
    {
        return $this->hasMany(ProjectProgress::class);
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function timeTracking()
    {
        return $this->hasMany(TimeTracking::class);
    }

    // Team relationships

    // Helper methods
    public function assignDeveloper(User $developer)
    {
        $this->update([
            'assigned_developer_id' => $developer->id,
            'assigned_at' => now(),
        ]);
    }

    public function startProject()
    {
        $this->update([
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);
    }

    public function updateActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }

    // Progress tracking helper methods
    public function getTotalHoursWorkedAttribute(): float
    {
        return $this->progressUpdates()->sum('hours_worked') ?? 0;
    }

    public function getTotalHoursPurchasedAttribute(): float
    {
        return $this->user->purchases()->where('status', 'paid')->sum('days') * 8; // Assuming 8 hours per day
    }

    public function getHoursRemainingAttribute(): float
    {
        return max(0, $this->total_hours_purchased - $this->total_hours_worked);
    }

    public function getOverallProgressAttribute(): int
    {
        $latestProgress = $this->progressUpdates()->latest('work_date')->first();
        return $latestProgress ? $latestProgress->progress_percentage : 0;
    }

    public function getRecentProgressAttribute()
    {
        return $this->progressUpdates()->latest('work_date')->limit(5)->get();
    }

    public function getTotalFilesAttribute(): int
    {
        return $this->files()->count();
    }

    public function getPublicFilesAttribute()
    {
        return $this->files()->where('is_public', true)->get();
    }
}
