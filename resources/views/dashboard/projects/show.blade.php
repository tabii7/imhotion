@extends('layouts.dashboard')

@section('page-title', $project->name)

@section('content')
<div class="p-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('dashboard.projects') }}" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-2">
                    <h1 class="text-3xl font-bold text-white">{{ $project->name }}</h1>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($project->status === 'in_progress') bg-blue-500/20 text-blue-400
                        @elseif($project->status === 'completed') bg-green-500/20 text-green-400
                        @elseif($project->status === 'on_hold') bg-orange-500/20 text-orange-400
                        @elseif($project->status === 'finalized') bg-purple-500/20 text-purple-400
                        @elseif($project->status === 'cancelled') bg-red-500/20 text-red-400
                        @else bg-gray-500/20 text-gray-400
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>
                <p class="text-gray-300">{{ $project->description }}</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard.projects.edit', $project) }}" class="bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                
                <form action="{{ route('dashboard.projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this project?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500/20 hover:bg-red-500/30 text-red-400 px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Project Details -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
                <h2 class="text-xl font-semibold text-white mb-6">Project Details</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Estimated Hours</label>
                        <p class="text-white text-lg font-semibold">{{ $project->estimated_hours }} hours</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Priority</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($project->priority === 'high') bg-red-500/20 text-red-400
                            @elseif($project->priority === 'medium') bg-yellow-500/20 text-yellow-400
                            @else bg-green-500/20 text-green-400
                            @endif">
                            {{ ucfirst($project->priority) }} Priority
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Created</label>
                        <p class="text-white">{{ $project->created_at->format('M d, Y \a\t g:i A') }}</p>
                    </div>
                    
                    @if($project->deadline)
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Deadline</label>
                        <p class="text-white">{{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Assigned Developer -->
            @if($project->assignedDeveloper)
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
                <h2 class="text-xl font-semibold text-white mb-6">Assigned Developer</h2>
                
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-brand-primary rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-xl">{{ substr($project->assignedDeveloper->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-white">{{ $project->assignedDeveloper->name }}</h3>
                        <p class="text-gray-400">{{ $project->assignedDeveloper->email }}</p>
                        @if($project->assignedDeveloper->skills)
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($project->assignedDeveloper->skills as $skill)
                            <span class="px-2 py-1 bg-slate-700/50 text-gray-300 text-xs rounded-full">{{ $skill }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Project Progress -->
            @if($project->progress && $project->progress->count() > 0)
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
                <h2 class="text-xl font-semibold text-white mb-6">Project Updates</h2>
                
                <div class="space-y-6">
                    @foreach($project->progress->sortByDesc('created_at') as $update)
                    <div class="border-l-2 border-brand-primary pl-6 pb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <h4 class="font-semibold text-white">{{ $update->title }}</h4>
                            <span class="text-xs text-gray-400">{{ $update->created_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-gray-300 mb-3">{{ $update->description }}</p>
                        <div class="flex items-center gap-4 text-sm text-gray-400">
                            <span>By: {{ $update->developer->name ?? 'System' }}</span>
                            @if($update->hours_worked)
                            <span>{{ $update->hours_worked }} hours worked</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Project Files -->
            @if($project->files && $project->files->count() > 0)
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
                <h2 class="text-xl font-semibold text-white mb-6">Project Files</h2>
                
                <div class="space-y-3">
                    @foreach($project->files as $file)
                    <div class="flex items-center justify-between p-4 bg-slate-700/30 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="text-white font-medium">{{ $file->filename }}</p>
                                <p class="text-gray-400 text-sm">{{ $file->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($file->path) }}" download class="text-brand-primary hover:text-brand-primary/80 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Quick Stats</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Status</span>
                        <span class="text-white font-medium">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Priority</span>
                        <span class="text-white font-medium">{{ ucfirst($project->priority) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Estimated Hours</span>
                        <span class="text-white font-medium">{{ $project->estimated_hours }}</span>
                    </div>
                    
                    @if($project->total_hours_worked)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Hours Worked</span>
                        <span class="text-white font-medium">{{ $project->total_hours_worked }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>
                
                <div class="space-y-3">
                    <div class="text-sm">
                        <p class="text-white">Project created</p>
                        <p class="text-gray-400">{{ $project->created_at->diffForHumans() }}</p>
                    </div>
                    
                    @if($project->updated_at != $project->created_at)
                    <div class="text-sm">
                        <p class="text-white">Last updated</p>
                        <p class="text-gray-400">{{ $project->updated_at->diffForHumans() }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
