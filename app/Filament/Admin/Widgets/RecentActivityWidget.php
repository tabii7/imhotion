<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Models\Project;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class RecentActivityWidget extends Widget
{
    protected static string $view = 'filament.widgets.recent-activity-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    public array $activities = [];

    public function mount(): void
    {
        $activities = collect();
        
        // Recent project activities
        $recentProjects = Project::with(['user', 'assignedDeveloper'])
            ->latest()
            ->limit(5)
            ->get();
            
        foreach ($recentProjects as $project) {
            $activities->push([
                'type' => 'project',
                'action' => 'created',
                'title' => "New project '{$project->name}' created",
                'description' => "Client: {$project->user->name} | Status: " . ucfirst($project->status),
                'time' => $project->created_at,
                'icon' => 'heroicon-o-folder',
                'color' => 'blue',
                'user' => $project->user->name ?? 'System',
            ]);
        }
        
        // Recent user registrations
        $recentUsers = User::latest()
            ->limit(3)
            ->get();
            
        foreach ($recentUsers as $user) {
            $activities->push([
                'type' => 'user',
                'action' => 'registered',
                'title' => "New user registered: {$user->name}",
                'description' => "Role: " . ucfirst($user->role) . " | Email: {$user->email}",
                'time' => $user->created_at,
                'icon' => 'heroicon-o-user-plus',
                'color' => 'green',
                'user' => $user->name,
            ]);
        }
        
        // Recent purchases
        $recentPurchases = Purchase::with('user')
            ->where('status', 'paid')
            ->latest()
            ->limit(3)
            ->get();
            
        foreach ($recentPurchases as $purchase) {
            $activities->push([
                'type' => 'purchase',
                'action' => 'completed',
                'title' => "Payment received: €{$purchase->amount}",
                'description' => "From: {$purchase->user->name} | Days: {$purchase->days}",
                'time' => $purchase->created_at,
                'icon' => 'heroicon-o-currency-euro',
                'color' => 'green',
                'user' => $purchase->user->name,
            ]);
        }
        
        // Recent team activities
        $recentTeams = Team::latest()
            ->limit(2)
            ->get();
            
        foreach ($recentTeams as $team) {
            $activities->push([
                'type' => 'team',
                'action' => 'created',
                'title' => "New team created: {$team->name}",
                'description' => "Team Lead: " . ($team->teamLead->name ?? 'Not assigned'),
                'time' => $team->created_at,
                'icon' => 'heroicon-o-user-group',
                'color' => 'purple',
                'user' => 'System',
            ]);
        }
        
        // Sort all activities by time and limit to 10
        $this->activities = $activities
            ->sortByDesc('time')
            ->take(10)
            ->values()
            ->toArray();
    }
    
    public function getActivityIcon(string $icon): string
    {
        return match($icon) {
            'heroicon-o-folder' => 'folder',
            'heroicon-o-user-plus' => 'user-plus',
            'heroicon-o-currency-euro' => 'currency-euro',
            'heroicon-o-user-group' => 'user-group',
            default => 'bell',
        };
    }
    
    public function getActivityColor(string $color): string
    {
        return match($color) {
            'blue' => 'text-blue-600 bg-blue-100 dark:text-blue-400 dark:bg-blue-900',
            'green' => 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900',
            'purple' => 'text-purple-600 bg-purple-100 dark:text-purple-400 dark:bg-purple-900',
            'yellow' => 'text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900',
            'red' => 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900',
            default => 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-900',
        };
    }
}


