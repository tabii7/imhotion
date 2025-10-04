@extends('layouts.admin')

@section('page-title', 'Team Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Team Management</h1>
                    <p class="text-gray-300">Manage teams and assign projects</p>
                </div>
                <a href="{{ route('admin.teams.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Create Team
                </a>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 backdrop-blur-sm border border-blue-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $stats['total_teams'] ?? 0 }}</div>
                    <div class="text-blue-300 font-medium">Total Teams</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-600/20 backdrop-blur-sm border border-green-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $stats['active_teams'] ?? 0 }}</div>
                    <div class="text-green-300 font-medium">Active Teams</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500/20 to-pink-600/20 backdrop-blur-sm border border-purple-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $stats['total_members'] ?? 0 }}</div>
                    <div class="text-purple-300 font-medium">Total Members</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-yellow-500/20 to-amber-600/20 backdrop-blur-sm border border-yellow-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $stats['unassigned_developers'] ?? 0 }}</div>
                    <div class="text-yellow-300 font-medium">Unassigned Developers</div>
                </div>
            </div>
        </div>

        <!-- Teams List -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
            <div class="px-8 py-6 border-b border-gray-700/30">
                <h3 class="text-2xl font-bold text-white">All Teams</h3>
            </div>
            <div class="divide-y divide-gray-700/30">
                @forelse($teams as $team)
                    <div class="p-8 hover:bg-white/5 transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-lg font-semibold text-white truncate">{{ $team->name }}</h4>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($team->status === 'active') bg-gradient-to-r from-green-500 to-emerald-500 text-white
                                            @else bg-gradient-to-r from-gray-500 to-gray-600 text-white @endif shadow-lg">
                                            {{ ucfirst($team->status) }}
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-blue-500 to-cyan-500 text-white shadow-lg">
                                            {{ $team->members_count }} members
                                        </span>
                                    </div>
                                </div>
                                
                                @if($team->description)
                                    <p class="text-gray-300 mb-4">{{ $team->description }}</p>
                                @endif
                                
                                <div class="flex items-center text-sm text-gray-400 mb-4">
                                    @if($team->teamLead)
                                        <span class="flex items-center mr-6">
                                            <i class="fas fa-crown mr-2"></i>
                                            Lead: {{ $team->teamLead->name }}
                                        </span>
                                    @endif
                                    <span class="flex items-center mr-6">
                                        <i class="fas fa-project-diagram mr-2"></i>
                                        {{ $team->projects_count }} projects
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-2"></i>
                                        Created {{ $team->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <!-- Team Members Preview -->
                                @if($team->members->count() > 0)
                                    <div class="flex items-center">
                                        <div class="flex -space-x-2">
                                            @foreach($team->members->take(5) as $member)
                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-gray-700">
                                                    {{ substr($member->name, 0, 1) }}
                                                </div>
                                            @endforeach
                                            @if($team->members->count() > 5)
                                                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-white text-xs font-bold border-2 border-gray-700">
                                                    +{{ $team->members->count() - 5 }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="ml-3 text-gray-400 text-sm">{{ $team->members->count() }} members</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="ml-6 flex-shrink-0 flex space-x-3">
                                <a href="{{ route('admin.teams.show', $team) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-700 to-gray-600 text-white rounded-xl hover:from-gray-600 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                    <i class="fas fa-eye mr-2"></i>
                                    View
                                </a>
                                <a href="{{ route('admin.teams.edit', $team) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-users text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">No teams found</h3>
                        <p class="text-gray-400 text-lg mb-6">Create your first team to get started.</p>
                        <a href="{{ route('admin.teams.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Create Team
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($teams->hasPages())
            <div class="mt-8">
                {{ $teams->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
