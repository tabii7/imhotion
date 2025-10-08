<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Models\Team;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class TeamPerformanceWidget extends Widget
{
    protected static string $view = 'filament.widgets.team-performance-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    public array $teamStats = [];
    public array $topPerformers = [];
    public array $teamPerformance = [];

    public function mount(): void
    {
        // Get team statistics
        $totalTeams = Team::count();
        $activeTeams = Team::where('status', 'active')->count();
        $totalDevelopers = User::where('role', 'developer')->count();
        $availableDevelopers = User::where('role', 'developer')
            ->where('is_available', true)
            ->count();
        
        $this->teamStats = [
            'total_teams' => $totalTeams,
            'active_teams' => $activeTeams,
            'total_developers' => $totalDevelopers,
            'available_developers' => $availableDevelopers,
            'utilization_rate' => $totalDevelopers > 0 ? round(($availableDevelopers / $totalDevelopers) * 100, 1) : 0,
        ];
        
        // Get top performing developers
        $this->topPerformers = User::where('role', 'developer')
            ->withCount(['assignedProjects as completed_projects' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->withCount(['assignedProjects as active_projects' => function ($query) {
                $query->whereIn('status', ['in_progress', 'editing']);
            }])
            ->orderBy('completed_projects', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($developer) {
                return [
                    'id' => $developer->id,
                    'name' => $developer->name,
                    'email' => $developer->email,
                    'completed_projects' => $developer->completed_projects,
                    'active_projects' => $developer->active_projects,
                    'is_available' => $developer->is_available,
                    'specialization' => $developer->specialization,
                    'experience_level' => $developer->experience_level,
                ];
            })
            ->toArray();
        
        // Get team performance data
        $this->teamPerformance = Team::with(['teamLead', 'members'])
            ->withCount(['projects as total_projects' => function ($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->withCount(['projects as completed_projects' => function ($query) {
                $query->whereIn('status', ['completed', 'finalized']);
            }])
            ->withCount(['projects as active_projects' => function ($query) {
                $query->whereIn('status', ['in_progress', 'editing']);
            }])
            ->get()
            ->map(function ($team) {
                $completionRate = $team->total_projects > 0 
                    ? round(($team->completed_projects / $team->total_projects) * 100, 1) 
                    : 0;
                
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'description' => $team->description,
                    'team_lead' => $team->teamLead->name ?? 'Not assigned',
                    'member_count' => $team->members->count(),
                    'total_projects' => $team->total_projects,
                    'completed_projects' => $team->completed_projects,
                    'active_projects' => $team->active_projects,
                    'completion_rate' => $completionRate,
                    'status' => $team->status,
                ];
            })
            ->sortByDesc('completion_rate')
            ->values()
            ->toArray();
    }
    
    public function getStatusColor(string $status): string
    {
        return match($status) {
            'active' => 'success',
            'inactive' => 'danger',
            default => 'gray',
        };
    }
    
    public function getExperienceColor(string $level): string
    {
        return match($level) {
            'senior' => 'success',
            'mid' => 'warning',
            'junior' => 'info',
            default => 'gray',
        };
    }
}


