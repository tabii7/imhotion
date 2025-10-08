@extends('layouts.admin')

@section('page-title', 'Project Details')
@section('page-description', 'View and manage project details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="fi-widget-card">
        <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Project Details & Management</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.custom-projects.index') }}" class="fi-btn-secondary inline-flex items-center px-4 py-2">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Projects
                    </a>
                    <a href="{{ route('filament.admin.resources.projects.edit', $project) }}" class="fi-btn inline-flex items-center px-4 py-2">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Project
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Project Details -->
        <div class="lg:col-span-2">
            <div class="fi-widget-card">
                <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project Name</label>
                            <p class="text-gray-900 dark:text-white text-lg font-semibold">{{ $project->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <div class="mt-2">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($project->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400
                                    @elseif($project->status === 'completed') bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400
                                    @elseif($project->status === 'on_hold') bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400
                                    @elseif($project->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400
                                    @elseif($project->status === 'finalized') bg-purple-100 text-purple-800 dark:bg-purple-500/20 dark:text-purple-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-500/20 dark:text-gray-400 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Client</label>
                            <p class="text-gray-900 dark:text-white">{{ $project->user->name }}</p>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $project->user->email }}</p>
                        </div>
                        
                        @if($project->assignedDeveloper)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assigned Developer</label>
                                <p class="text-gray-900 dark:text-white">{{ $project->assignedDeveloper->name }}</p>
                                <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $project->assignedDeveloper->email }}</p>
                            </div>
                        @endif
                        
                        @if($project->delivery_date)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Delivery Date</label>
                                <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Created</label>
                            <p class="text-gray-900 dark:text-white">{{ $project->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    @if($project->topic)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $project->topic }}</p>
                        </div>
                    @endif

                    @if($project->progress)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Progress</label>
                            <div class="mt-2">
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <span class="font-medium">Project Progress</span>
                                    <span class="font-semibold">{{ $project->progress }}% Complete</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 h-3 rounded-full transition-all duration-500" style="width: {{ $project->progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions Panel -->
        <div class="space-y-6">
            <!-- Assign Developer -->
            @if(!$project->assignedDeveloper)
                <div class="fi-widget-card">
                    <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assign Developer</h3>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.custom-projects.assign-developer', $project) }}">
                            @csrf
                            <div class="mb-4">
                                <label for="developer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Developer</label>
                                <select id="developer_id" name="developer_id" required class="fi-input w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Choose a developer</option>
                                    @foreach($availableDevelopers as $developer)
                                        <option value="{{ $developer->id }}">{{ $developer->name }} ({{ $developer->experience_level ?? 'Developer' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="fi-btn w-full px-4 py-3">
                                <i class="fas fa-user-plus mr-2"></i>
                                Assign Developer
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Update Status -->
            <div class="fi-widget-card">
                <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Update Status</h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.custom-projects.update-status', $project) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Status</label>
                            <select id="status" name="status" required class="fi-input w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="in_progress" {{ $project->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="on_hold" {{ $project->status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                <option value="finalized" {{ $project->status === 'finalized' ? 'selected' : '' }}>Finalized</option>
                                <option value="cancelled" {{ $project->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="fi-btn w-full px-4 py-3">
                            <i class="fas fa-save mr-2"></i>
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="fi-widget-card">
                <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Stats</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Requirements</span>
                            <span class="text-gray-900 dark:text-white font-bold">{{ $project->requirements->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Activities</span>
                            <span class="text-gray-900 dark:text-white font-bold">{{ $project->activities->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Documents</span>
                            <span class="text-gray-900 dark:text-white font-bold">{{ $project->documents->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Time Logs</span>
                            <span class="text-gray-900 dark:text-white font-bold">{{ $project->timeLogs->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Requirements -->
    @if($project->requirements->count() > 0)
        <div class="fi-widget-card">
            <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project Requirements</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($project->requirements as $requirement)
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-gray-900 dark:text-white font-semibold text-lg">{{ $requirement->title }}</h4>
                                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $requirement->description }}</p>
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        <span class="flex items-center mr-6">
                                            <i class="fas fa-user mr-2"></i>
                                            {{ $requirement->user->name }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar mr-2"></i>
                                            {{ $requirement->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-6 flex-shrink-0">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($requirement->status === 'approved') bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400 @endif">
                                        {{ ucfirst($requirement->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Activities -->
    @if($project->activities->count() > 0)
        <div class="fi-widget-card">
            <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Activities</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($project->activities->take(10) as $activity)
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    {{ substr($activity->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-gray-900 dark:text-white font-semibold">{{ $activity->user->name }}</h4>
                                        <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $activity->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400">{{ $activity->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Project Documents -->
    @if($project->documents->count() > 0)
        <div class="fi-widget-card">
            <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Project Documents</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($project->documents as $document)
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-3">
                                <i class="fas fa-file text-purple-600 dark:text-purple-400 text-lg"></i>
                                <a href="{{ route('admin.custom-project-documents.download', $document) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                            <h4 class="text-gray-900 dark:text-white font-medium truncate">{{ $document->name }}</h4>
                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                <div>{{ $document->mime_type }}</div>
                                <div>{{ $document->size ? number_format($document->size / 1024, 1) . ' KB' : 'Unknown size' }}</div>
                                <div>{{ $document->created_at->format('M d, Y') }}</div>
                                <div>by {{ $document->uploadedBy->name ?? 'Unknown' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Updates -->
    @if($recentUpdates->count() > 0)
        <div class="fi-widget-card">
            <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Updates</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($recentUpdates as $update)
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-tasks text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-gray-900 dark:text-white font-semibold">{{ $update->project_name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $update->created_at->format('M d, Y \a\t H:i') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-500/20 dark:text-green-400 text-xs font-medium rounded-full">
                                        {{ ucfirst(str_replace('_', ' ', $update->status)) }}
                                    </span>
                                    @if($update->progress_percentage > 0)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400 text-xs font-medium rounded-full">
                                            {{ $update->progress_percentage }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-600 dark:text-gray-400">{{ $update->description }}</p>
                            </div>
                            
                            @if($update->tasks_completed && count($update->tasks_completed) > 0)
                                <div class="mb-4">
                                    <h5 class="text-sm font-medium text-green-600 dark:text-green-400 mb-2">Tasks Completed:</h5>
                                    <ul class="space-y-1">
                                        @foreach($update->tasks_completed as $task)
                                            <li class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <i class="fas fa-check-circle text-green-500 dark:text-green-400 mr-2"></i>
                                                {{ $task }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $update->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Time Logs -->
    @if($project->timeLogs->count() > 0)
        <div class="fi-widget-card">
            <div class="fi-widget-header px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Time Logs</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-gray-900 dark:text-white">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Developer</th>
                                <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Date</th>
                                <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Hours</th>
                                <th class="text-left py-3 px-2 text-sm font-medium text-gray-600 dark:text-gray-400">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->timeLogs as $timeLog)
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <td class="py-3 px-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 bg-gradient-to-r from-green-600 to-emerald-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                {{ substr($timeLog->user->name, 0, 1) }}
                                            </div>
                                            <span class="text-gray-900 dark:text-white">{{ $timeLog->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2 text-gray-600 dark:text-gray-400">{{ $timeLog->date->format('M d, Y') }}</td>
                                    <td class="py-3 px-2">
                                        <span class="font-bold text-green-600 dark:text-green-400">{{ $timeLog->hours_spent }}</span>
                                    </td>
                                    <td class="py-3 px-2 text-gray-600 dark:text-gray-400">{{ $timeLog->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection