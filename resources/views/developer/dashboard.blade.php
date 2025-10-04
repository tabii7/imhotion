@extends('layouts.developer')

@section('page-title', 'Welcome back, ' . Auth::user()->name . '!')

@section('content')
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 backdrop-blur-sm border border-blue-500/30 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-white mb-2">{{ $stats['total_assigned'] ?? 0 }}</div>
                                <div class="text-blue-300 font-medium">Total Projects</div>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-tasks text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500/20 to-emerald-600/20 backdrop-blur-sm border border-green-500/30 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-white mb-2">{{ $stats['completed'] ?? 0 }}</div>
                                <div class="text-green-300 font-medium">Completed</div>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-check-circle text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-yellow-500/20 to-amber-600/20 backdrop-blur-sm border border-yellow-500/30 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-white mb-2">{{ $stats['in_progress'] ?? 0 }}</div>
                                <div class="text-yellow-300 font-medium">In Progress</div>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-clock text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500/20 to-pink-600/20 backdrop-blur-sm border border-purple-500/30 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-white mb-2">{{ $stats['hours_logged_this_month'] ?? 0 }}</div>
                                <div class="text-purple-300 font-medium">Hours This Month</div>
                            </div>
                            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-hourglass-half text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Current Projects -->
                <div class="lg:col-span-2">
                    <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                        <div class="px-8 py-6 border-b border-gray-700/30">
                            <div class="flex justify-between items-center">
                                <h3 class="text-2xl font-bold text-white">Current Projects</h3>
                                <a href="{{ route('developer.projects') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                    <i class="fas fa-arrow-right mr-2"></i>
                                    View all projects
                                </a>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-700/30">
                            @forelse($assignedProjects as $project)
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
                                            
                                            <div class="flex items-center text-sm text-gray-400">
                                                <span class="flex items-center mr-6">
                                                    <i class="fas fa-calendar mr-2"></i>
                                                    {{ $project->delivery_date ? \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') : 'No deadline' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-6 flex-shrink-0">
                                            <a href="{{ route('developer.projects.show', $project) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-700 to-gray-600 text-white rounded-xl hover:from-gray-600 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
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
                                    <h3 class="text-xl font-semibold text-white mb-3">No projects assigned</h3>
                                    <p class="text-gray-400 text-lg">You don't have any projects assigned yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                    <div class="px-8 py-6 border-b border-gray-700/30">
                        <h3 class="text-2xl font-bold text-white">Recent Activity</h3>
                    </div>
                    <div class="p-8">
                        @forelse($recentActivities as $activity)
                            <div class="flex items-start space-x-4 mb-6">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-{{ $activity->type === 'time_log' ? 'clock' : 'file' }} text-white text-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-medium">{{ $activity->description }}</p>
                                    <p class="text-gray-400 text-sm mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-history text-white text-2xl"></i>
                                </div>
                                <p class="text-gray-400 text-lg">No recent activity</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
@endsection
