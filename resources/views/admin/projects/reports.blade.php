@extends('layouts.admin')

@section('page-title', 'Project Reports')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Project Reports</h1>
                    <p class="text-gray-300">Comprehensive analytics and insights for all projects</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.custom-projects.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Projects
                    </a>
                </div>
            </div>
        </div>

        <!-- Key Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-8">
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-6 text-center">
                <div class="text-3xl font-bold text-white mb-2">{{ $stats['total_projects'] }}</div>
                <div class="text-gray-300 font-medium text-sm">Total Projects</div>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-6 text-center">
                <div class="text-3xl font-bold text-blue-300 mb-2">{{ $stats['active_projects'] }}</div>
                <div class="text-gray-300 font-medium text-sm">Active Projects</div>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-6 text-center">
                <div class="text-3xl font-bold text-green-300 mb-2">{{ $stats['completed_projects'] }}</div>
                <div class="text-gray-300 font-medium text-sm">Completed</div>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-6 text-center">
                <div class="text-3xl font-bold text-yellow-300 mb-2">{{ $stats['on_hold_projects'] }}</div>
                <div class="text-gray-300 font-medium text-sm">On Hold</div>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-6 text-center">
                <div class="text-3xl font-bold text-red-300 mb-2">{{ $stats['cancelled_projects'] }}</div>
                <div class="text-gray-300 font-medium text-sm">Cancelled</div>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-6 text-center">
                <div class="text-3xl font-bold text-orange-300 mb-2">{{ $stats['overdue_projects'] }}</div>
                <div class="text-gray-300 font-medium text-sm">Overdue</div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Projects by Status -->
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Projects by Status</h3>
                <div class="space-y-4">
                    @foreach($performanceMetrics['projects_by_status'] as $status => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 rounded-full 
                                    @if($status === 'in_progress') bg-blue-500
                                    @elseif($status === 'completed') bg-green-500
                                    @elseif($status === 'on_hold') bg-yellow-500
                                    @elseif($status === 'cancelled') bg-red-500
                                    @elseif($status === 'finalized') bg-purple-500
                                    @else bg-gray-500 @endif">
                                </div>
                                <span class="text-white font-medium">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                            </div>
                            <span class="text-white font-bold text-lg">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Developer Performance -->
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Developer Performance</h3>
                <div class="space-y-4">
                    @forelse($performanceMetrics['developer_performance'] as $performance)
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-white font-medium">{{ $performance['developer'] }}</span>
                                <span class="text-green-400 font-bold">{{ $performance['completion_rate'] }}%</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-400">
                                <span>{{ $performance['completed_projects'] }}/{{ $performance['total_projects'] }} projects</span>
                                <div class="w-20 bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full" style="width: {{ $performance['completion_rate'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 text-center py-4">No developer performance data available</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Monthly Progress Chart -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8 mb-8">
            <h3 class="text-2xl font-bold text-white mb-6">Monthly Project Creation</h3>
            <div class="grid grid-cols-12 gap-2">
                @foreach($performanceMetrics['monthly_progress'] as $month => $count)
                    <div class="text-center">
                        <div class="bg-gradient-to-t from-blue-500 to-purple-500 rounded-lg p-2 mb-2" style="height: {{ max(20, ($count / max($performanceMetrics['monthly_progress'])) * 100) }}px;">
                            <span class="text-white text-xs font-bold">{{ $count }}</span>
                        </div>
                        <span class="text-gray-400 text-xs">{{ \Carbon\Carbon::parse($month)->format('M Y') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Detailed Project List -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8">
            <h3 class="text-2xl font-bold text-white mb-6">All Projects Overview</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-white">
                    <thead>
                        <tr class="border-b border-gray-700/30">
                            <th class="text-left py-4 px-2">Project</th>
                            <th class="text-left py-4 px-2">Client</th>
                            <th class="text-left py-4 px-2">Developer</th>
                            <th class="text-left py-4 px-2">Status</th>
                            <th class="text-left py-4 px-2">Progress</th>
                            <th class="text-left py-4 px-2">Deadline</th>
                            <th class="text-left py-4 px-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr class="border-b border-gray-700/20 hover:bg-white/5 transition-colors">
                                <td class="py-4 px-2">
                                    <div>
                                        <div class="font-medium text-white">{{ $project->name }}</div>
                                        <div class="text-sm text-gray-400">{{ $project->topic ? Str::limit($project->topic, 50) : 'No description' }}</div>
                                    </div>
                                </td>
                                <td class="py-4 px-2">
                                    <div class="text-white">{{ $project->user->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $project->user->email }}</div>
                                </td>
                                <td class="py-4 px-2">
                                    @if($project->assignedDeveloper)
                                        <div class="text-white">{{ $project->assignedDeveloper->name }}</div>
                                        <div class="text-sm text-gray-400">{{ $project->assignedDeveloper->email }}</div>
                                    @else
                                        <span class="text-orange-400">Not Assigned</span>
                                    @endif
                                </td>
                                <td class="py-4 px-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($project->status === 'in_progress') bg-blue-500/20 text-blue-300
                                        @elseif($project->status === 'completed') bg-green-500/20 text-green-300
                                        @elseif($project->status === 'on_hold') bg-yellow-500/20 text-yellow-300
                                        @elseif($project->status === 'cancelled') bg-red-500/20 text-red-300
                                        @elseif($project->status === 'finalized') bg-purple-500/20 text-purple-300
                                        @else bg-gray-500/20 text-gray-300 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </td>
                                <td class="py-4 px-2">
                                    @if($project->progress)
                                        <div class="flex items-center space-x-2">
                                            <div class="w-16 bg-gray-700 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full" style="width: {{ $project->progress }}%"></div>
                                            </div>
                                            <span class="text-sm text-white">{{ $project->progress }}%</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">No progress</span>
                                    @endif
                                </td>
                                <td class="py-4 px-2">
                                    @if($project->delivery_date)
                                        <div class="text-white">{{ \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') }}</div>
                                        @if($project->delivery_date < now() && !in_array($project->status, ['completed', 'cancelled', 'finalized']))
                                            <div class="text-sm text-red-400">Overdue</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">No deadline</span>
                                    @endif
                                </td>
                                <td class="py-4 px-2">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.custom-projects.show', $project) }}" class="text-blue-400 hover:text-blue-300 transition-colors" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.project-reports.show', $project) }}" class="text-green-400 hover:text-green-300 transition-colors" title="View Report">
                                            <i class="fas fa-chart-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
