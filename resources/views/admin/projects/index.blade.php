@extends('layouts.admin')

@section('page-title', 'Project Management')
@section('page-description', 'Manage and monitor all projects')

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="fi-wi-stats-overview-stat p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="fi-wi-stats-overview-stat-description">Total Projects</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['total_projects'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-project-diagram text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="fi-wi-stats-overview-stat p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="fi-wi-stats-overview-stat-description">Active Projects</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['active_projects'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tasks text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="fi-wi-stats-overview-stat p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="fi-wi-stats-overview-stat-description">Completed</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['completed'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="fi-wi-stats-overview-stat p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="fi-wi-stats-overview-stat-description">On Hold</p>
                    <p class="fi-wi-stats-overview-stat-value">{{ $stats['on_hold'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-pause text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="fi-widget-card">
        <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">All Projects</h3>
                <a href="{{ route('filament.admin.resources.projects.create') }}" class="fi-btn fi-btn-primary inline-flex items-center px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Add Project
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Developer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($projects as $project)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold mr-4">
                                        {{ substr($project->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $project->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($project->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $project->user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $project->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->assignedDeveloper)
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $project->assignedDeveloper->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $project->assignedDeveloper->email }}</div>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400 italic">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="fi-badge fi-badge-sm px-3 py-1 text-xs font-medium rounded-full
                                    @if($project->status === 'in_progress') fi-color-primary
                                    @elseif($project->status === 'completed') fi-color-success
                                    @elseif($project->status === 'on_hold') fi-color-warning
                                    @elseif($project->status === 'cancelled') fi-color-danger
                                    @elseif($project->status === 'finalized') fi-color-info
                                    @else fi-color-gray @endif">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $project->progress ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $project->progress ?? 0 }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $project->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.custom-projects.show', $project) }}" 
                                       class="fi-btn fi-btn-secondary text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('filament.admin.resources.projects.edit', $project) }}" 
                                       class="fi-btn fi-btn-primary text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-folder-open text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No projects found</p>
                                    <p class="text-sm">Get started by creating your first project.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection