<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'team_lead_id',
        'status',
        'specializations',
    ];

    protected $casts = [
        'specializations' => 'array',
    ];

    /**
     * Get the team lead
     */
    public function teamLead()
    {
        return $this->belongsTo(User::class, 'team_lead_id');
    }

    /**
     * Get team members
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    /**
     * Get team projects
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_teams')
            ->withPivot('assigned_at')
            ->withTimestamps();
    }

    /**
     * Scope for active teams
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get team performance metrics
     */
    public function getPerformanceMetrics()
    {
        $totalProjects = $this->projects()->count();
        $completedProjects = $this->projects()->whereIn('status', ['completed', 'finalized'])->count();
        $activeProjects = $this->projects()->whereIn('status', ['in_progress', 'editing'])->count();
        
        $completionRate = $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100, 1) : 0;
        
        return [
            'total_projects' => $totalProjects,
            'completed_projects' => $completedProjects,
            'active_projects' => $activeProjects,
            'completion_rate' => $completionRate,
        ];
    }
}


