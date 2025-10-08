@extends('layouts.administrator')

@section('page-title', 'Project Management')
@section('page-subtitle', 'Manage and monitor all projects')

@section('content')
<!-- Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-white">Project Management</h1>
        <p class="text-gray-400 mt-2">Monitor and manage all projects across the platform</p>
    </div>
    <div class="flex items-center space-x-4">
        <div class="relative">
            <input type="text" placeholder="Search projects..." class="bg-gray-800/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
        </div>
        <select class="bg-gray-800/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="">All Status</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="on_hold">On Hold</option>
            <option value="finalized">Finalized</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
</div>

<!-- Projects Grid -->
<div class="grid gap-8">
    @forelse($projects as $project)
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8 hover:bg-white/10 transition-all duration-300">
            <!-- Project Header -->
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-4 mb-3">
                        <h3 class="text-2xl font-bold text-white truncate">{{ $project->name }}</h3>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 bg-gradient-to-r from-blue-500 to-purple-500 text-white text-sm font-medium rounded-full">
                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                            </span>
                            @if($project->progress)
                                <span class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-full">
                                    {{ $project->progress }}%
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($project->topic)
                        <p class="text-gray-300 text-lg mb-4">{{ $project->topic }}</p>
                    @endif
                    
                    <!-- Project Info -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm text-gray-400">Client</div>
                                <div class="text-white font-medium">{{ $project->user->name }}</div>
                            </div>
                        </div>
                        
                        @if($project->assignedDeveloper)
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-code text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-400">Developer</div>
                                    <div class="text-white font-medium">{{ $project->assignedDeveloper->name }}</div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation text-white text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-400">Developer</div>
                                    <div class="text-orange-400 font-medium">Not Assigned</div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm text-gray-400">Deadline</div>
                                <div class="text-white font-medium">
                                    {{ $project->delivery_date ? \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') : 'No deadline' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    @if($project->progress)
                        <div class="mb-6">
                            <div class="flex justify-between text-sm text-gray-300 mb-2">
                                <span class="font-medium">Project Progress</span>
                                <span class="font-semibold">{{ $project->progress }}% Complete</span>
                            </div>
                            <div class="w-full bg-gray-700/50 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-500" style="width: {{ $project->progress }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col space-y-3 ml-6">
                    <a href="{{ route('administrator.projects.show', $project) }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg text-center">
                        <i class="fas fa-eye mr-2"></i>View Details
                    </a>
                    @if(!$project->assignedDeveloper)
                        <button onclick="assignDeveloper({{ $project->id }})" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i>Assign Developer
                        </button>
                    @endif
                </div>
            </div>
            
            <!-- Project Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-white">{{ $project->requirements->count() }}</div>
                    <div class="text-sm text-gray-400">Requirements</div>
                </div>
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-white">{{ $project->documents->count() }}</div>
                    <div class="text-sm text-gray-400">Documents</div>
                </div>
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-white">{{ $project->activities->count() }}</div>
                    <div class="text-sm text-gray-400">Activities</div>
                </div>
                <div class="bg-white/5 rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-white">{{ $project->timeLogs->count() }}</div>
                    <div class="text-sm text-gray-400">Time Logs</div>
                </div>
            </div>
            
            <!-- Recent Documents -->
            @if($project->documents && $project->documents->count() > 0)
                <div class="mb-6">
                    <div class="text-sm font-medium text-purple-300 mb-3">Recent Documents</div>
                    <div class="space-y-2">
                        @foreach($project->documents->take(3) as $document)
                            <div class="flex items-center justify-between text-sm text-gray-300 hover:bg-gray-700/30 rounded-lg p-2 transition-colors">
                                <div class="flex items-center flex-1 min-w-0">
                                    <i class="fas fa-file text-purple-400 mr-2 flex-shrink-0"></i>
                                    <span class="truncate">{{ $document->name }}</span>
                                </div>
                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <span class="text-gray-400">{{ $document->created_at->format('M d') }}</span>
                                    <a href="{{ route('administrator.project-documents.download', $document) }}" class="text-blue-400 hover:text-blue-300 transition-colors" title="Download">
                                        <i class="fas fa-download text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        @if($project->documents->count() > 3)
                            <div class="text-xs text-gray-400 text-center py-1">
                                +{{ $project->documents->count() - 3 }} more documents
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            <!-- Project Footer -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-700/30">
                <div class="flex items-center space-x-4 text-sm text-gray-400">
                    <span class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        Created {{ $project->created_at->format('M d, Y') }}
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Updated {{ $project->updated_at->diffForHumans() }}
                    </span>
                </div>
                <div class="text-sm text-gray-500">
                    ID: {{ $project->id }}
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-folder-open text-white text-4xl"></i>
            </div>
            <h3 class="text-2xl font-semibold text-white mb-3">No projects found</h3>
            <p class="text-gray-400 text-lg">No projects have been created yet.</p>
        </div>
    @endforelse
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
// Available developers data from server
const availableDevelopers = @json($availableDevelopers);

function assignDeveloper(projectId) {
    // Populate developers dropdown
    const select = document.getElementById('developer_id');
    select.innerHTML = '<option value="">Choose a developer</option>';
    
    availableDevelopers.forEach(dev => {
        const option = document.createElement('option');
        option.value = dev.id;
        option.textContent = dev.name + (dev.experience_level ? ` (${dev.experience_level})` : '');
        select.appendChild(option);
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