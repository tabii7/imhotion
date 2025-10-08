@extends('layouts.developer')

@section('page-title', 'Project Details')

@section('content')
<!-- Header -->
<div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8 mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-white">{{ $project->name }}</h1>
            <p class="text-gray-300 mt-2">{{ $project->topic }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('developer.projects') }}" class="text-gray-300 hover:text-white font-medium transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Projects
            </a>
            <button onclick="updateProjectStatus()" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-edit mr-2"></i>Update Status
            </button>
        </div>
    </div>
</div>

<!-- Project Status -->
<div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium shadow-lg
                @if($project->status === 'completed') bg-gradient-to-r from-green-500 to-emerald-500 text-white
                @elseif($project->status === 'in_progress') bg-gradient-to-r from-blue-500 to-cyan-500 text-white
                @elseif($project->status === 'pending') bg-gradient-to-r from-yellow-500 to-amber-500 text-white
                @elseif($project->status === 'on_hold') bg-gradient-to-r from-gray-500 to-gray-600 text-white
                @else bg-gradient-to-r from-red-500 to-rose-500 text-white @endif">
                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
            </span>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium shadow-lg
                @if($project->priority === 'urgent') bg-gradient-to-r from-red-500 to-rose-500 text-white
                @elseif($project->priority === 'high') bg-gradient-to-r from-orange-500 to-red-500 text-white
                @elseif($project->priority === 'medium') bg-gradient-to-r from-yellow-500 to-orange-500 text-white
                @else bg-gradient-to-r from-gray-500 to-gray-600 text-white @endif">
                {{ ucfirst($project->priority ?? 'Not set') }} Priority
            </span>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-300">Progress</div>
            <div class="text-2xl font-bold text-white">{{ $project->progress ?? 0 }}%</div>
        </div>
    </div>
    
    @if($project->progress)
        <div class="w-full bg-gray-700/50 rounded-full h-3">
            <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-500" style="width: {{ $project->progress }}%"></div>
        </div>
    @endif
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Project Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Client Requirements -->
        @if($project->client_requirements)
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                <div class="p-8 border-b border-gray-700/30">
                    <h3 class="text-xl font-bold text-white">Client Requirements</h3>
                </div>
                <div class="p-8">
                    <div class="prose max-w-none text-gray-300">
                        {!! nl2br(e($project->client_requirements)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Administrator Notes -->
        @if($project->administrator_notes)
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                <div class="p-8 border-b border-gray-700/30">
                    <h3 class="text-xl font-bold text-white">Administrator Notes</h3>
                </div>
                <div class="p-8">
                    <div class="prose max-w-none text-gray-300">
                        {!! nl2br(e($project->administrator_notes)) !!}
                    </div>
                </div>
            </div>
        @endif

        <!-- Developer Notes -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
            <div class="p-8 border-b border-gray-700/30">
                <h3 class="text-xl font-bold text-white">Your Notes</h3>
            </div>
            <div class="p-8">
                @if($project->developer_notes)
                    <div class="prose max-w-none mb-6 text-gray-300">
                        {!! nl2br(e($project->developer_notes)) !!}
                    </div>
                @endif
                <button onclick="editDeveloperNotes()" class="bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-edit mr-2"></i>{{ $project->developer_notes ? 'Edit Notes' : 'Add Notes' }}
                </button>
            </div>
        </div>

        <!-- Project Documents -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
            <div class="p-8 border-b border-gray-700/30">
                <h3 class="text-xl font-bold text-white">Project Documents</h3>
            </div>
            <div class="p-8">
                @forelse($project->documents as $document)
                    <div class="flex items-center justify-between p-4 bg-gray-800/50 border border-gray-700/30 rounded-xl mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-file text-blue-400 mr-4 text-xl"></i>
                            <div>
                                <div class="font-medium text-white">{{ $document->name }}</div>
                                <div class="text-sm text-gray-400">{{ $document->mime_type }} • {{ $document->file_size ? number_format($document->file_size / 1024, 1) . ' KB' : 'Unknown size' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('developer.project-documents.download', $document) }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                                <i class="fas fa-download text-xl"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400">No documents uploaded yet.</p>
                @endforelse
                
                <button onclick="uploadDocument()" class="mt-6 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-upload mr-2"></i>Upload Document
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Project Info -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
            <div class="p-8 border-b border-gray-700/30">
                <h3 class="text-xl font-bold text-white">Project Information</h3>
            </div>
            <div class="p-8 space-y-6">
                <div>
                    <div class="text-sm font-medium text-gray-300 mb-2">Deadline</div>
                    <div class="text-lg font-semibold text-white">
                        {{ $project->delivery_date ? \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') : 'No deadline' }}
                    </div>
                </div>
                
                <div>
                    <div class="text-sm font-medium text-gray-300 mb-2">Assigned</div>
                    <div class="text-lg font-semibold text-white">
                        {{ $project->assigned_at ? \Carbon\Carbon::parse($project->assigned_at)->format('M d, Y') : 'Not assigned' }}
                    </div>
                </div>
                
                <div>
                    <div class="text-sm font-medium text-gray-300 mb-2">Started</div>
                    <div class="text-lg font-semibold text-white">
                        {{ $project->started_at ? \Carbon\Carbon::parse($project->started_at)->format('M d, Y') : 'Not started' }}
                    </div>
                </div>
                
                <div>
                    <div class="text-sm font-medium text-gray-300 mb-2">Last Activity</div>
                    <div class="text-lg font-semibold text-white">
                        {{ $project->last_activity_at ? \Carbon\Carbon::parse($project->last_activity_at)->diffForHumans() : 'No activity' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Logging -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
            <div class="p-8 border-b border-gray-700/30">
                <h3 class="text-xl font-bold text-white">Time Logging</h3>
            </div>
            <div class="p-8">
                <button onclick="logTime()" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg mb-6">
                    <i class="fas fa-clock mr-2"></i>Log Time
                </button>
                
                <div class="space-y-3">
                    @forelse($project->timeLogs as $log)
                        <div class="flex justify-between items-center p-4 bg-gray-800/50 border border-gray-700/30 rounded-xl">
                            <div>
                                <div class="text-sm font-medium text-white">{{ $log->hours_spent }} hours</div>
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($log->work_date)->format('M d, Y') }}</div>
                            </div>
                            <div class="text-sm text-gray-300">{{ $log->description }}</div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">No time logged yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
            <div class="p-8 border-b border-gray-700/30">
                <h3 class="text-xl font-bold text-white">Quick Actions</h3>
            </div>
            <div class="p-8 space-y-4">
                <button onclick="updateProjectStatus()" class="w-full bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-edit mr-2"></i>Update Status
                </button>
                <button onclick="logTime()" class="w-full bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-clock mr-2"></i>Log Time
                </button>
                <button onclick="uploadDocument()" class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-upload mr-2"></i>Upload Document
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-900/95 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-2xl max-w-md w-full">
            <div class="p-8">
                <h3 class="text-xl font-bold text-white mb-6">Update Project Status</h3>
                <form id="statusForm">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-3">Status</label>
                        <select id="projectStatus" class="w-full bg-gray-800/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ $project->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $project->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="on_hold" {{ $project->status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-3">Progress (%)</label>
                        <input type="number" id="projectProgress" min="0" max="100" value="{{ $project->progress ?? 0 }}" class="w-full bg-gray-800/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-3">Notes</label>
                        <textarea id="projectNotes" rows="3" class="w-full bg-gray-800/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add any notes about the project...">{{ $project->developer_notes }}</textarea>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeStatusModal()" class="px-6 py-3 border border-gray-600/50 rounded-xl text-gray-300 hover:text-white hover:bg-gray-800/50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateProjectStatus() {
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function editDeveloperNotes() {
    // Implementation for editing developer notes
    alert('Edit notes functionality coming soon!');
}

function logTime() {
    // Implementation for time logging
    alert('Time logging functionality coming soon!');
}

function uploadDocument() {
    // Implementation for document upload
    alert('Document upload functionality coming soon!');
}

document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('status', document.getElementById('projectStatus').value);
    formData.append('progress', document.getElementById('projectProgress').value);
    formData.append('developer_notes', document.getElementById('projectNotes').value);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    fetch(`{{ route('developer.projects.update-status', $project) }}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating project status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating project status');
    });
});

// Close modal when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});
</script>
@endsection
