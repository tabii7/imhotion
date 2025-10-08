<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Project;
use App\Models\Purchase;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class KpisWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Get total users
        $totalUsers = User::count();
        
        // Get new users this month
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Get active projects
        $activeProjects = Project::whereIn('status', ['in_progress', 'pending', 'editing'])->count();
        
        // Get completed projects this month
        $completedThisMonth = Project::where('status', 'completed')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();
        
        // Get total revenue from purchases
        $totalRevenue = Purchase::where('status', 'paid')->sum('amount');
        
        // Get revenue this month
        $revenueThisMonth = Purchase::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        
        // Get total teams
        $totalTeams = Team::count();
        
        // Get available developers
        $availableDevelopers = User::where('role', 'developer')
            ->where('is_available', true)
            ->count();

        return [
            Stat::make('Total Users', $totalUsers)
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
                
            Stat::make('New Users This Month', $newUsersThisMonth)
                ->description('New registrations')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('success'),
                
            Stat::make('Active Projects', $activeProjects)
                ->description('Currently in progress')
                ->descriptionIcon('heroicon-m-folder-open')
                ->color('warning'),
                
            Stat::make('Completed This Month', $completedThisMonth)
                ->description('Projects finished')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
                
            Stat::make('Total Revenue', '€' . number_format($totalRevenue, 2))
                ->description('All time earnings')
                ->descriptionIcon('heroicon-m-currency-euro')
                ->color('primary'),
                
            Stat::make('Revenue This Month', '€' . number_format($revenueThisMonth, 2))
                ->description('Current month earnings')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
                
            Stat::make('Total Teams', $totalTeams)
                ->description('Active teams')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
                
            Stat::make('Available Developers', $availableDevelopers)
                ->description('Ready to work')
                ->descriptionIcon('heroicon-m-code-bracket')
                ->color('warning'),
        ];
    }
}
