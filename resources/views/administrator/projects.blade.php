@extends('layouts.administrator')

@section('page-title', 'Project Management')
@section('page-subtitle', 'Manage and monitor all projects')

@section('content')
<!-- Projects List -->
<div class="stats-card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-white">All Projects</h3>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" placeholder="Search projects..." class="bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
            <select class="bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="on_hold">On Hold</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>
    
    <div class="space-y-4">
        @forelse($projects as $project)
            <div class="project-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="project-title">{{ $project->title }}</h4>
                            <div class="flex items-center space-x-3">
                                <span class="status-badge status-{{ str_replace('_', '-', $project->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                                @if($project->progress)
                                    <span class="status-badge bg-purple-500 text-white">
                                        {{ $project->progress }}% Complete
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        @if($project->topic)
                            <p class="project-description">{{ $project->topic }}</p>
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
                    
                    <div class="ml-6 flex-shrink-0 flex space-x-3">
                        <a href="{{ route('administrator.projects.show', $project) }}" class="btn btn-secondary">
                            <i class="fas fa-eye"></i>
                            View Details
                        </a>
                        @if(!$project->assignedDeveloper)
                            <button onclick="assignDeveloper({{ $project->id }})" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i>
                                Assign Developer
                            </button>
                        @endif
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

<!-- Pagination -->
@if($projects->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $projects->links() }}
    </div>
@endif

<!-- Assign Developer Modal -->
<div id="assignDeveloperModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-2xl shadow-xl max-w-md w-full">
            <div class="px-8 py-6 border-b border-gray-700">
                <h3 class="text-2xl font-bold text-white">Assign Developer</h3>
            </div>
            <form id="assignDeveloperForm" method="POST">
                @csrf
                <div class="p-8">
                    <div class="mb-6">
                        <label for="developer_id" class="block text-sm font-medium text-gray-300 mb-2">Select Developer</label>
                        <select id="developer_id" name="developer_id" required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Choose a developer</option>
                            <!-- Developers will be loaded here -->
                        </select>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeAssignModal()" class="btn btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Assign Developer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function assignDeveloper(projectId) {
    // Load available developers
    fetch('/api/developers/available')
        .then(response => response.json())
        .then(developers => {
            const select = document.getElementById('developer_id');
            select.innerHTML = '<option value="">Choose a developer</option>';
            developers.forEach(dev => {
                const option = document.createElement('option');
                option.value = dev.id;
                option.textContent = dev.name;
                select.appendChild(option);
            });
        });
    
    // Set form action
    document.getElementById('assignDeveloperForm').action = `/administrator/projects/${projectId}/assign-developer`;
    
    // Show modal
    document.getElementById('assignDeveloperModal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assignDeveloperModal').classList.add('hidden');
}
</script>
@endsection