@extends('layouts.administrator')

@section('page-title', 'Project Progress Tracking')
@section('page-subtitle', 'Monitor developer progress and project files across all projects')

@section('content')
<!-- Filters -->
<div class="stats-card mb-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="form-label">Search Projects</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Search by project name or client...">
        </div>
        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Developer</label>
            <select name="developer" class="form-select">
                <option value="">All Developers</option>
                @foreach($developers as $developer)
                    <option value="{{ $developer->id }}" {{ request('developer') == $developer->id ? 'selected' : '' }}>
                        {{ $developer->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="btn btn-primary w-full">
                <i class="fas fa-search mr-2"></i>
                Filter
            </button>
        </div>
    </form>
</div>

<!-- Projects with Progress -->
<div class="space-y-6">
    @forelse($projects as $project)
        <div class="stats-card">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-project-diagram text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">{{ $project->title }}</h3>
                        <p class="text-gray-400">Client: {{ $project->user->name }} • Developer: {{ $project->assignedDeveloper->name ?? 'Unassigned' }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="status-badge status-{{ str_replace('_', '-', $project->status) }}">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                    <a href="{{ route('administrator.progress.show', $project) }}" class="btn btn-primary">
                        <i class="fas fa-eye mr-2"></i>
                        View Progress
                    </a>
                </div>
            </div>

            <!-- Progress Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-white">{{ $project->overall_progress }}%</div>
                            <div class="text-gray-400 text-sm">Overall Progress</div>
                        </div>
                        <i class="fas fa-chart-line text-blue-400 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-white">{{ number_format($project->total_hours_worked, 1) }}</div>
                            <div class="text-gray-400 text-sm">Hours Worked</div>
                        </div>
                        <i class="fas fa-clock text-green-400 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-white">{{ number_format($project->hours_remaining, 1) }}</div>
                            <div class="text-gray-400 text-sm">Hours Remaining</div>
                        </div>
                        <i class="fas fa-hourglass-half text-yellow-400 text-xl"></i>
                    </div>
                </div>
                
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-white">{{ $project->total_files }}</div>
                            <div class="text-gray-400 text-sm">Files Uploaded</div>
                        </div>
                        <i class="fas fa-file-upload text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Progress Entries -->
            @if($project->recent_progress->count() > 0)
                <div class="mb-4">
                    <h4 class="text-lg font-semibold text-white mb-3">Recent Progress</h4>
                    <div class="space-y-3">
                        @foreach($project->recent_progress->take(3) as $progress)
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
                    <h4 class="text-lg font-semibold text-white mb-3">Recent Files</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->public_files->take(5) as $file)
                            <a href="{{ route('administrator.progress.download', [$project, $file]) }}" 
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
                <i class="fas fa-chart-line text-white text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-white mb-3">No Projects Found</h3>
            <p class="text-gray-400 text-lg">No projects match your current filters.</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($projects->hasPages())
    <div class="mt-8">
        {{ $projects->links() }}
    </div>
@endif
@endsection


