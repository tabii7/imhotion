@extends('layouts.dashboard')

@section('page-title', 'Project Progress')
@section('page-subtitle', 'Track your project progress and view developer work')

@section('content')
<!-- Hours Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ number_format($stats['total_hours_purchased'], 1) }}</div>
                <div class="stats-label">Hours Purchased</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-shopping-cart text-white text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ number_format($stats['total_hours_used'], 1) }}</div>
                <div class="stats-label">Hours Used</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-clock text-white text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ number_format($stats['total_hours_remaining'], 1) }}</div>
                <div class="stats-label">Hours Remaining</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-hourglass-half text-white text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ $stats['active_projects'] }}</div>
                <div class="stats-label">Active Projects</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-project-diagram text-white text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Hours Usage Chart -->
<div class="stats-card mb-8">
    <h3 class="text-xl font-bold text-white mb-6">Hours Usage Overview</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-300">Hours Used</span>
                <span class="text-blue-400 font-semibold">{{ number_format($stats['total_hours_used'], 1) }}h</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-4">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-4 rounded-full transition-all duration-500" 
                     style="width: {{ $stats['total_hours_purchased'] > 0 ? ($stats['total_hours_used'] / $stats['total_hours_purchased']) * 100 : 0 }}%"></div>
            </div>
            <p class="text-gray-400 text-sm mt-2">
                {{ $stats['total_hours_purchased'] > 0 ? number_format(($stats['total_hours_used'] / $stats['total_hours_purchased']) * 100, 1) : 0 }}% of purchased hours used
            </p>
        </div>
        
        <div>
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-300">Hours Remaining</span>
                <span class="text-yellow-400 font-semibold">{{ number_format($stats['total_hours_remaining'], 1) }}h</span>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-4">
                <div class="bg-gradient-to-r from-yellow-500 to-amber-600 h-4 rounded-full transition-all duration-500" 
                     style="width: {{ $stats['total_hours_purchased'] > 0 ? ($stats['total_hours_remaining'] / $stats['total_hours_purchased']) * 100 : 0 }}%"></div>
            </div>
            <p class="text-gray-400 text-sm mt-2">
                {{ $stats['total_hours_remaining'] > 0 ? 'Hours available for new projects' : 'All hours have been used' }}
            </p>
        </div>
    </div>
</div>

<!-- Projects Progress -->
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-2xl font-bold text-white">Your Projects</h3>
        <a href="{{ route('dashboard') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Dashboard
        </a>
    </div>

    @forelse($projects as $project)
        <div class="stats-card">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-project-diagram text-white text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-white">{{ $project->title }}</h4>
                        <p class="text-gray-400">
                            Developer: {{ $project->assignedDeveloper->name ?? 'Unassigned' }} • 
                            Status: {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-blue-400 font-semibold text-lg">{{ $project->overall_progress }}%</span>
                    <a href="{{ route('progress.show', $project) }}" class="btn btn-primary">
                        <i class="fas fa-eye mr-2"></i>
                        View Details
                    </a>
                </div>
            </div>

            <!-- Project Progress Bar -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-300">Project Progress</span>
                    <span class="text-blue-400 font-semibold">{{ $project->overall_progress }}%</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-3">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500" 
                         style="width: {{ $project->overall_progress }}%"></div>
                </div>
            </div>

            <!-- Project Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-lg font-bold text-white">{{ number_format($project->total_hours_worked, 1) }}h</div>
                            <div class="text-gray-400 text-sm">Hours Worked</div>
                        </div>
                        <i class="fas fa-clock text-blue-400 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-lg font-bold text-white">{{ $project->public_files->count() }}</div>
                            <div class="text-gray-400 text-sm">Files Shared</div>
                        </div>
                        <i class="fas fa-file-upload text-green-400 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-lg font-bold text-white">{{ $project->recent_progress->count() }}</div>
                            <div class="text-gray-400 text-sm">Progress Entries</div>
                        </div>
                        <i class="fas fa-chart-line text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Progress -->
            @if($project->recent_progress->count() > 0)
                <div class="mb-4">
                    <h5 class="text-lg font-semibold text-white mb-3">Recent Progress</h5>
                    <div class="space-y-3">
                        @foreach($project->recent_progress->take(2) as $progress)
                            <div class="flex items-center justify-between p-3 bg-gray-800/30 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-calendar-day text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-white font-medium">{{ $progress->work_date->format('M d, Y') }}</div>
                                        <div class="text-gray-400 text-sm">{{ $progress->developer->name }} • {{ $progress->formatted_hours }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-blue-400 font-semibold">{{ $progress->progress_percentage }}%</span>
                                    <span class="status-badge status-{{ $progress->status_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $progress->status)) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Recent Files -->
            @if($project->public_files->count() > 0)
                <div>
                    <h5 class="text-lg font-semibold text-white mb-3">Recent Files</h5>
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->public_files->take(3) as $file)
                            <a href="{{ route('progress.download', [$project, $file]) }}" 
                               class="flex items-center space-x-2 px-3 py-2 bg-gray-800/50 rounded-lg hover:bg-gray-700/50 transition-colors">
                                <i class="{{ $file->file_icon }} text-blue-400"></i>
                                <span class="text-white text-sm">{{ $file->original_name }}</span>
                                <span class="text-gray-400 text-xs">{{ $file->formatted_size }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="text-center py-12">
            <div class="w-20 h-20 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-project-diagram text-white text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-3">No Projects Yet</h3>
            <p class="text-gray-400 text-lg">You haven't created any projects yet.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-4">
                <i class="fas fa-plus mr-2"></i>
                Create Your First Project
            </a>
        </div>
    @endforelse
</div>
@endsection


