@extends('layouts.administrator')

@section('page-title', 'Project Details')
@section('page-subtitle', 'View and manage project information')

@section('content')
<!-- Project Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Project Details -->
    <div class="lg:col-span-2">
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Project Information</h3>
            <div class="space-y-6">
                <div>
                    <label class="text-sm font-medium text-gray-300">Project Title</label>
                    <p class="text-white text-lg font-semibold">{{ $project->name }}</p>
                </div>
                
                @if($project->topic)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Description</label>
                        <p class="text-gray-300">{{ $project->topic }}</p>
                    </div>
                @endif
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-300">Status</label>
                        <div class="mt-2">
                            <span class="status-badge status-{{ str_replace('_', '-', $project->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                        </div>
                    </div>
                    
                    @if($project->progress)
                        <div>
                            <label class="text-sm font-medium text-gray-300">Progress</label>
                            <div class="mt-2">
                                <div class="flex justify-between text-sm text-gray-300 mb-2">
                                    <span>{{ $project->progress }}% Complete</span>
                                </div>
                                <div class="w-full bg-gray-700/50 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-500" style="width: {{ $project->progress }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-300">Client</label>
                        <p class="text-white">{{ $project->user->name }}</p>
                        <p class="text-gray-400 text-sm">{{ $project->user->email }}</p>
                    </div>
                    
                    @if($project->assignedDeveloper)
                        <div>
                            <label class="text-sm font-medium text-gray-300">Assigned Developer</label>
                            <p class="text-white">{{ $project->assignedDeveloper->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $project->assignedDeveloper->email }}</p>
                        </div>
                    @endif
                </div>
                
                @if($project->delivery_date)
                    <div>
                        <label class="text-sm font-medium text-gray-300">Delivery Date</label>
                        <p class="text-white">{{ \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') }}</p>
                    </div>
                @endif
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-300">Created</label>
                        <p class="text-white">{{ $project->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-300">Last Updated</label>
                        <p class="text-white">{{ $project->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Panel -->
    <div class="space-y-8">
        <!-- Assign Developer -->
        @if(!$project->assignedDeveloper)
            <div class="stats-card">
                <h3 class="text-xl font-bold text-white mb-6">Assign Developer</h3>
                <form method="POST" action="{{ route('administrator.projects.assign-developer', $project) }}">
                    @csrf
                    <div class="mb-6">
                        <label for="developer_id" class="block text-sm font-medium text-gray-300 mb-2">Select Developer</label>
                        <select id="developer_id" name="developer_id" required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Choose a developer</option>
                            @foreach($availableDevelopers as $developer)
                                <option value="{{ $developer->id }}">{{ $developer->name }} ({{ $developer->experience_level ?? 'Developer' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full btn btn-primary justify-center">
                        <i class="fas fa-user-plus"></i>
                        Assign Developer
                    </button>
                </form>
            </div>
        @endif

        <!-- Update Status -->
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Update Status</h3>
            <form method="POST" action="{{ route('administrator.projects.update-status', $project) }}">
                @csrf
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">New Status</label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="pending" {{ $project->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $project->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="on_hold" {{ $project->status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $project->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="w-full btn btn-success justify-center">
                    <i class="fas fa-save"></i>
                    Update Status
                </button>
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Quick Stats</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-300">Requirements</span>
                    <span class="text-white font-bold">{{ $project->requirements->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-300">Activities</span>
                    <span class="text-white font-bold">{{ $project->activities->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-300">Documents</span>
                    <span class="text-white font-bold">{{ $project->documents->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-300">Time Logs</span>
                    <span class="text-white font-bold">{{ $project->timeLogs->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Requirements -->
@if($project->requirements->count() > 0)
    <div class="mb-8">
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Project Requirements</h3>
            <div class="space-y-4">
                @foreach($project->requirements as $requirement)
                    <div class="project-card">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <h4 class="project-title">{{ $requirement->title }}</h4>
                                <p class="project-description">{{ $requirement->description }}</p>
                                <div class="flex items-center text-sm text-gray-400 mt-2">
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
                                <span class="status-badge status-{{ $requirement->status === 'approved' ? 'in-progress' : 'pending' }}">
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

<!-- Project Activities -->
@if($project->activities->count() > 0)
    <div class="mb-8">
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Recent Activities</h3>
            <div class="space-y-4">
                @foreach($project->activities->take(10) as $activity)
                    <div class="project-card">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ substr($activity->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-white font-semibold">{{ $activity->user->name }}</h4>
                                    <span class="text-gray-400 text-sm">{{ $activity->created_at->format('M d, Y H:i') }}</span>
                                </div>
                                <p class="text-gray-300">{{ $activity->description }}</p>
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
    <div class="mb-8">
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Project Documents</h3>
            <div class="space-y-4">
                @foreach($project->documents as $document)
                    <div class="project-card">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-1 min-w-0">
                                <i class="fas fa-file text-purple-400 mr-3 flex-shrink-0"></i>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-white font-medium truncate">{{ $document->name }}</h4>
                                    <div class="flex items-center text-sm text-gray-400 mt-1">
                                        <span class="mr-4">{{ $document->mime_type }}</span>
                                        <span class="mr-4">{{ $document->size ? number_format($document->size / 1024, 1) . ' KB' : 'Unknown size' }}</span>
                                        <span>{{ $document->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 flex-shrink-0">
                                <span class="text-xs text-gray-400">
                                    by {{ $document->uploadedBy->name ?? 'Unknown' }}
                                </span>
                                <a href="{{ route('administrator.project-documents.download', $document) }}" class="text-blue-400 hover:text-blue-300 transition-colors" title="Download">
                                    <i class="fas fa-download text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Recent Updates -->
@if($recentUpdates->count() > 0)
    <div class="mb-8">
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Recent Updates</h3>
            <div class="space-y-4">
                @foreach($recentUpdates as $update)
                    <div class="project-card">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-tasks text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold">{{ $update->project_name }}</h4>
                                    <p class="text-sm text-gray-400">{{ $update->created_at->format('M d, Y \a\t H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-medium rounded-full">
                                    {{ ucfirst(str_replace('_', ' ', $update->status)) }}
                                </span>
                                @if($update->progress_percentage > 0)
                                    <span class="px-3 py-1 bg-gradient-to-r from-blue-500 to-purple-500 text-white text-xs font-medium rounded-full">
                                        {{ $update->progress_percentage }}%
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-gray-300">{{ $update->description }}</p>
                        </div>
                        
                        @if($update->tasks_completed && count($update->tasks_completed) > 0)
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-green-300 mb-2">Tasks Completed:</h5>
                                <ul class="space-y-1">
                                    @foreach($update->tasks_completed as $task)
                                        <li class="flex items-center text-sm text-gray-300">
                                            <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                            {{ $task }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <div class="text-xs text-gray-500">
                                {{ $update->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection