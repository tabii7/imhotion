<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectOverviewWidget extends Widget
{
    protected static string $view = 'filament.widgets.project-overview-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    public array $projectStats = [];
    public array $recentProjects = [];
    public array $statusCounts = [];

    public function mount(): void
    {
        // Get project status counts
        $this->statusCounts = [
            'pending' => Project::where('status', 'pending')->count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'editing' => Project::where('status', 'editing')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'finalized' => Project::where('status', 'finalized')->count(),
            'cancelled' => Project::where('status', 'cancelled')->count(),
        ];
        
        // Get total projects
        $totalProjects = Project::count();
        
        // Get projects by priority
        $priorityCounts = Project::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
        
        // Get recent projects
        $this->recentProjects = Project::with(['user', 'assignedDeveloper'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'status' => $project->status,
                    'client' => $project->user->name ?? 'Unknown',
                    'developer' => $project->assignedDeveloper->name ?? 'Unassigned',
                    'created_at' => $project->created_at->diffForHumans(),
                    'priority' => $project->priority ?? 'normal',
                ];
            })
            ->toArray();
        
        // Calculate project statistics
        $this->projectStats = [
            'total' => $totalProjects,
            'active' => $this->statusCounts['in_progress'] + $this->statusCounts['editing'],
            'completed' => $this->statusCounts['completed'] + $this->statusCounts['finalized'],
            'pending' => $this->statusCounts['pending'],
            'cancelled' => $this->statusCounts['cancelled'],
            'completion_rate' => $totalProjects > 0 ? round((($this->statusCounts['completed'] + $this->statusCounts['finalized']) / $totalProjects) * 100, 1) : 0,
            'priority_counts' => $priorityCounts,
        ];
    }
    
    public function getStatusColor(string $status): string
    {
        return match($status) {
            'pending' => 'warning',
            'in_progress' => 'primary',
            'editing' => 'info',
            'completed' => 'success',
            'finalized' => 'success',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }
    
    public function getPriorityColor(string $priority): string
    {
        return match($priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
            default => 'gray',
        };
    }
}


