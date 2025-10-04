@extends('layouts.admin')

@section('page-title', 'Project Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Project Management</h1>
                    <p class="text-gray-300">Manage and monitor all projects</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 backdrop-blur-sm border border-blue-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $stats['total_projects'] ?? 0 }}</div>
                    <div class="text-blue-300 font-medium text-sm">Total Projects</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-600/20 backdrop-blur-sm border border-green-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $stats['active_projects'] ?? 0 }}</div>
                    <div class="text-green-300 font-medium text-sm">Active Projects</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500/20 to-pink-600/20 backdrop-blur-sm border border-purple-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $stats['completed_projects'] ?? 0 }}</div>
                    <div class="text-purple-300 font-medium text-sm">Completed</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-yellow-500/20 to-amber-600/20 backdrop-blur-sm border border-yellow-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $stats['on_hold'] ?? 0 }}</div>
                    <div class="text-yellow-300 font-medium text-sm">On Hold</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-orange-500/20 to-red-600/20 backdrop-blur-sm border border-orange-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-orange-300 font-medium text-sm">Pending</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-red-500/20 to-pink-600/20 backdrop-blur-sm border border-red-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $stats['overdue'] ?? 0 }}</div>
                    <div class="text-red-300 font-medium text-sm">Overdue</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl mb-8">
            <div class="px-8 py-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-300 mb-2">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search projects..."
                            class="w-full px-4 py-2 bg-white/10 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                        <select id="status" name="status"
                            class="w-full px-4 py-2 bg-white/10 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="team" class="block text-sm font-medium text-gray-300 mb-2">Team</label>
                        <select id="team" name="team"
                            class="w-full px-4 py-2 bg-white/10 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Teams</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ request('team') == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="developer" class="block text-sm font-medium text-gray-300 mb-2">Developer</label>
                        <select id="developer" name="developer"
                            class="w-full px-4 py-2 bg-white/10 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Developers</option>
                            @foreach($developers as $developer)
                                <option value="{{ $developer->id }}" {{ request('developer') == $developer->id ? 'selected' : '' }}>
                                    {{ $developer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="fas fa-search mr-2"></i>
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Projects List -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
            <div class="px-8 py-6 border-b border-gray-700/30">
                <h3 class="text-2xl font-bold text-white">All Projects</h3>
            </div>
            <div class="divide-y divide-gray-700/30">
                @forelse($projects as $project)
                    <div class="p-8 hover:bg-white/5 transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-lg font-semibold text-white truncate">{{ $project->title }}</h4>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($project->status === 'completed') bg-gradient-to-r from-green-500 to-emerald-500 text-white
                                            @elseif($project->status === 'in_progress') bg-gradient-to-r from-blue-500 to-cyan-500 text-white
                                            @elseif($project->status === 'pending') bg-gradient-to-r from-yellow-500 to-amber-500 text-white
                                            @elseif($project->status === 'on_hold') bg-gradient-to-r from-orange-500 to-red-500 text-white
                                            @else bg-gradient-to-r from-gray-500 to-gray-600 text-white @endif shadow-lg">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                        @if($project->progress)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg">
                                                {{ $project->progress }}% Complete
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($project->topic)
                                    <p class="text-gray-300 mb-4">{{ $project->topic }}</p>
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
                                    @if($project->teams->count() > 0)
                                        <span class="flex items-center mr-6">
                                            <i class="fas fa-users mr-2"></i>
                                            {{ $project->teams->pluck('name')->join(', ') }}
                                        </span>
                                    @endif
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $project->delivery_date ? \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') : 'No deadline' }}
                                    </span>
                                </div>

                                <!-- Progress Bar -->
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
                            </div>
                            
                            <div class="ml-6 flex-shrink-0">
                                <a href="{{ route('admin.projects.show', $project) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-700 to-gray-600 text-white rounded-xl hover:from-gray-600 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-folder-open text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">No projects found</h3>
                        <p class="text-gray-400 text-lg">No projects match your current filters.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="mt-8">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
