@extends('layouts.admin')

@section('page-title', 'Admin Dashboard')
@section('page-description', 'Welcome to the Imhotion Admin Panel')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Projects -->
        <div class="fi-wi-stats-overview-stat p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="fi-wi-stats-overview-stat-description">Total Projects</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['total_projects'] ?? 0 }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +12% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-project-diagram text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="fi-wi-stats-overview-stat p-6">
                    <div class="flex items-center justify-between">
                        <div>
                    <p class="fi-wi-stats-overview-stat-description">Active Projects</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['active_projects'] ?? 0 }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +8% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tasks text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        </div>
        </div>

        <!-- Completed Projects -->
        <div class="fi-wi-stats-overview-stat p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="fi-wi-stats-overview-stat-description">Completed</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['completed_projects'] ?? 0 }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +15% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
            
        <!-- Active Developers -->
        <div class="fi-wi-stats-overview-stat p-6">
                    <div class="flex items-center justify-between">
                        <div>
                    <p class="fi-wi-stats-overview-stat-description">Active Developers</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['active_developers'] ?? 0 }}</p>
                    <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +3 new this month
                    </p>
                        </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-orange-600 dark:text-orange-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- On Hold Projects -->
        <div class="fi-wi-stats-overview-stat p-6">
                    <div class="flex items-center justify-between">
                        <div>
                    <p class="fi-wi-stats-overview-stat-description">On Hold</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['on_hold_projects'] ?? 0 }}</p>
                        </div>
                <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-pause text-yellow-600 dark:text-yellow-400"></i>
                </div>
                </div>
            </div>
            
        <!-- Overdue Projects -->
        <div class="fi-wi-stats-overview-stat p-6">
                    <div class="flex items-center justify-between">
                        <div>
                    <p class="fi-wi-stats-overview-stat-description">Overdue</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['overdue_projects'] ?? 0 }}</p>
                        </div>
                <div class="w-10 h-10 bg-red-100 dark:bg-red-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <!-- Total Hours -->
        <div class="fi-wi-stats-overview-stat p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="fi-wi-stats-overview-stat-description">Total Hours</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['total_hours_logged'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Projects -->
            <div class="lg:col-span-2">
            <div class="fi-widget-card">
                <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Projects</h3>
                        <a href="{{ route('admin.custom-projects.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 text-sm font-medium">
                            View all <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($recentProjects as $project)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold">
                                        {{ substr($project->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $project->name }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->user->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        @if($project->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400
                                        @elseif($project->status === 'completed') bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400
                                        @elseif($project->status === 'on_hold') bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400
                                        @elseif($project->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400
                                        @elseif($project->status === 'finalized') bg-purple-100 text-purple-800 dark:bg-purple-500/20 dark:text-purple-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-500/20 dark:text-gray-400 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                    <a href="{{ route('admin.custom-projects.show', $project) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-folder-open text-gray-400 dark:text-gray-500 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No projects found</h3>
                                <p class="text-gray-600 dark:text-gray-400">No projects have been created yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                    </div>
                </div>

        <!-- Quick Actions & System Status -->
        <div class="space-y-6">
                <!-- Quick Actions -->
            <div class="fi-widget-card">
                <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
                    </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('filament.admin.resources.projects.create') }}" class="flex items-center w-full p-3 bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors">
                        <i class="fas fa-plus mr-3"></i>
                        Create New Project
                    </a>
                    <a href="{{ route('admin.custom-reports.create') }}" class="flex items-center w-full p-3 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-lg hover:bg-green-100 dark:hover:bg-green-500/20 transition-colors">
                        <i class="fas fa-chart-bar mr-3"></i>
                            Generate Report
                        </a>
                    <a href="{{ route('admin.developers.index') }}" class="flex items-center w-full p-3 bg-purple-50 dark:bg-purple-500/10 text-purple-700 dark:text-purple-400 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-500/20 transition-colors">
                        <i class="fas fa-users mr-3"></i>
                        Manage Developers
                    </a>
                    <a href="{{ route('filament.admin.resources.users.index') }}" class="flex items-center w-full p-3 bg-orange-50 dark:bg-orange-500/10 text-orange-700 dark:text-orange-400 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-500/20 transition-colors">
                        <i class="fas fa-user-cog mr-3"></i>
                        User Management
                    </a>
            </div>
        </div>

            <!-- System Status -->
            <div class="fi-widget-card">
                <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">System Status</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Database</span>
                        <span class="flex items-center text-green-600 dark:text-green-400">
                            <i class="fas fa-circle text-xs mr-2"></i>
                            Online
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Storage</span>
                        <span class="flex items-center text-green-600 dark:text-green-400">
                            <i class="fas fa-circle text-xs mr-2"></i>
                            Healthy
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">API</span>
                        <span class="flex items-center text-green-600 dark:text-green-400">
                            <i class="fas fa-circle text-xs mr-2"></i>
                            Active
                        </span>
                            </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Last Backup</span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">2 hours ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection