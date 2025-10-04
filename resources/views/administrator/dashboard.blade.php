@extends('layouts.administrator')

@section('page-title', 'Administrator Dashboard')
@section('page-subtitle', 'Overview of projects, developers, and system performance')

@section('content')
<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ $stats['total_projects'] ?? 0 }}</div>
                <div class="stats-label">Total Projects</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-project-diagram text-white text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ $stats['completed_projects'] ?? 0 }}</div>
                <div class="stats-label">Completed</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-check-circle text-white text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ $stats['pending_projects'] ?? 0 }}</div>
                <div class="stats-label">In Progress</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-clock text-white text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ $stats['available_developers'] ?? 0 }}</div>
                <div class="stats-label">Available Developers</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-users text-white text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Projects -->
    <div class="lg:col-span-2">
        <div class="stats-card">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">Recent Projects</h3>
                <a href="{{ route('administrator.projects') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right"></i>
                    View all projects
                </a>
            </div>
            <div class="space-y-4">
                @forelse($recentProjects as $project)
                    <div class="project-card">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="project-title">{{ $project->title }}</h4>
                            <span class="status-badge status-{{ str_replace('_', '-', $project->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                        </div>
                        @if($project->topic)
                            <p class="project-description">{{ $project->topic }}</p>
                        @endif
                        
                        <div class="flex items-center text-sm text-gray-400 mb-4">
                            <span class="flex items-center mr-6">
                                <i class="fas fa-user mr-2"></i>
                                {{ $project->user->name }}
                            </span>
                            @if($project->assignedDeveloper)
                                <span class="flex items-center mr-6">
                                    <i class="fas fa-code mr-2"></i>
                                    {{ $project->assignedDeveloper->name }}
                                </span>
                            @endif
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                {{ $project->delivery_date ? \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') : 'No deadline' }}
                            </span>
                        </div>

                        @if($project->progress)
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-300 mb-2">
                                    <span class="font-medium">Progress</span>
                                    <span class="font-semibold">{{ $project->progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-700/50 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-500" style="width: {{ $project->progress }}%"></div>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-end">
                            <a href="{{ route('administrator.projects.show', $project) }}" class="btn btn-secondary">
                                <i class="fas fa-eye"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-folder-open text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">No projects found</h3>
                        <p class="text-gray-400 text-lg">No projects have been created yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions & Pending Requirements -->
    <div class="space-y-8">
        <!-- Quick Actions -->
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Quick Actions</h3>
            <div class="space-y-4">
                <a href="{{ route('administrator.projects') }}" class="w-full btn btn-primary justify-center">
                    <i class="fas fa-tasks"></i>
                    Manage Projects
                </a>
                <a href="{{ route('administrator.developers') }}" class="w-full btn btn-success justify-center">
                    <i class="fas fa-users"></i>
                    View Developers
                </a>
                <a href="{{ route('administrator.reports') }}" class="w-full btn btn-secondary justify-center">
                    <i class="fas fa-chart-bar"></i>
                    View Reports
                </a>
            </div>
        </div>

        <!-- Pending Requirements -->
        @if($pendingRequirements->count() > 0)
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Pending Requirements</h3>
            <div class="space-y-4">
                @foreach($pendingRequirements->take(5) as $requirement)
                    <div class="flex items-center justify-between p-3 bg-gray-800/50 rounded-lg">
                        <div>
                            <h4 class="text-white font-semibold text-sm">{{ $requirement->title }}</h4>
                            <p class="text-gray-400 text-xs">{{ $requirement->project->title }}</p>
                        </div>
                        <span class="status-badge status-pending">Pending</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection