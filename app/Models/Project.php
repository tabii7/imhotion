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
        'title',
        'topic',
        'status',
        'start_date',
        'end_date',
        'delivery_date',
        'day_budget',
        'total_days',
        'days_used',
        'weekend_days',
        'notes',

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

    // Team relationships
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'project_teams')
            ->withPivot(['assigned_at'])
            ->withTimestamps();
    }

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
}
