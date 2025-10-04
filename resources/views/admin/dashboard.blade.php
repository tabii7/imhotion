@extends('layouts.admin')

@section('page-title', 'Administration Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Administration Dashboard</h1>
            <p class="text-gray-300">Manage projects, teams, and monitor system performance</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 backdrop-blur-sm border border-blue-500/30 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">{{ $stats['total_projects'] ?? 0 }}</div>
                            <div class="text-blue-300 font-medium">Total Projects</div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-project-diagram text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-600/20 backdrop-blur-sm border border-green-500/30 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">{{ $stats['active_projects'] ?? 0 }}</div>
                            <div class="text-green-300 font-medium">Active Projects</div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-tasks text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500/20 to-pink-600/20 backdrop-blur-sm border border-purple-500/30 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">{{ $stats['total_teams'] ?? 0 }}</div>
                            <div class="text-purple-300 font-medium">Total Teams</div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-yellow-500/20 to-amber-600/20 backdrop-blur-sm border border-yellow-500/30 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-3xl font-bold text-white mb-2">{{ $stats['active_developers'] ?? 0 }}</div>
                            <div class="text-yellow-300 font-medium">Active Developers</div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-code text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Projects -->
            <div class="lg:col-span-2">
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                    <div class="px-8 py-6 border-b border-gray-700/30">
                        <div class="flex justify-between items-center">
                            <h3 class="text-2xl font-bold text-white">Recent Projects</h3>
                            <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                <i class="fas fa-arrow-right mr-2"></i>
                                View all projects
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-700/30">
                        @forelse($recentProjects as $project)
                            <div class="p-8 hover:bg-white/5 transition-all duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="text-lg font-semibold text-white truncate">{{ $project->title }}</h4>
                                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                                @if($project->status === 'completed') bg-gradient-to-r from-green-500 to-emerald-500 text-white
                                                @elseif($project->status === 'in_progress') bg-gradient-to-r from-blue-500 to-cyan-500 text-white
                                                @elseif($project->status === 'pending') bg-gradient-to-r from-yellow-500 to-amber-500 text-white
                                                @else bg-gradient-to-r from-gray-500 to-gray-600 text-white @endif shadow-lg">
                                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                            </span>
                                        </div>
                                        <p class="text-gray-300 mb-4">{{ $project->topic }}</p>
                                        
                                        <div class="flex items-center text-sm text-gray-400">
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
                                    </div>
                                    <div class="ml-6 flex-shrink-0">
                                        <a href="{{ route('admin.projects.show', $project) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-700 to-gray-600 text-white rounded-xl hover:from-gray-600 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                            <i class="fas fa-eye mr-2"></i>
                                            View
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
                                <p class="text-gray-400 text-lg">No projects have been created yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Team Performance & Quick Actions -->
            <div class="space-y-8">
                <!-- Team Performance -->
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                    <div class="px-8 py-6 border-b border-gray-700/30">
                        <h3 class="text-2xl font-bold text-white">Team Performance</h3>
                    </div>
                    <div class="p-8">
                        @forelse($teamPerformance as $team)
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h4 class="text-white font-semibold">{{ $team['name'] }}</h4>
                                    <p class="text-gray-400 text-sm">{{ $team['member_count'] }} members</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-white font-bold">{{ $team['active_projects'] }}</div>
                                    <div class="text-gray-400 text-sm">Active Projects</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-white text-2xl"></i>
                                </div>
                                <p class="text-gray-400 text-lg">No teams found</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                    <div class="px-8 py-6 border-b border-gray-700/30">
                        <h3 class="text-2xl font-bold text-white">Quick Actions</h3>
                    </div>
                    <div class="p-8 space-y-4">
                        <a href="{{ route('admin.teams.create') }}" class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Create Team
                        </a>
                        <a href="{{ route('admin.reports.create') }}" class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Generate Report
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="w-full flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="fas fa-tasks mr-2"></i>
                            Manage Projects
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Status Distribution Chart -->
        @if(!empty($projectStatusDistribution))
        <div class="mt-8">
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                <div class="px-8 py-6 border-b border-gray-700/30">
                    <h3 class="text-2xl font-bold text-white">Project Status Distribution</h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @foreach($projectStatusDistribution as $status => $count)
                            <div class="text-center">
                                <div class="text-3xl font-bold text-white mb-2">{{ $count }}</div>
                                <div class="text-gray-300 capitalize">{{ str_replace('_', ' ', $status) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
