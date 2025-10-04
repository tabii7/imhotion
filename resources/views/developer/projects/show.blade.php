@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
            <p class="text-gray-600 mt-2">{{ $project->topic }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('developer.projects') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Projects
            </a>
            <button onclick="updateProjectStatus()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <i class="fas fa-edit mr-2"></i>Update Status
            </button>
        </div>
    </div>

    <!-- Project Status -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($project->status === 'completed') bg-green-100 text-green-800
                    @elseif($project->status === 'in_progress') bg-blue-100 text-blue-800
                    @elseif($project->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($project->status === 'on_hold') bg-gray-100 text-gray-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($project->priority === 'urgent') bg-red-100 text-red-800
                    @elseif($project->priority === 'high') bg-orange-100 text-orange-800
                    @elseif($project->priority === 'medium') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($project->priority ?? 'Not set') }} Priority
                </span>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Progress</div>
                <div class="text-2xl font-bold text-gray-900">{{ $project->progress ?? 0 }}%</div>
            </div>
        </div>
        
        @if($project->progress)
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $project->progress }}%"></div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Project Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Client Requirements -->
            @if($project->client_requirements)
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Client Requirements</h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none">
                            {!! nl2br(e($project->client_requirements)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Administrator Notes -->
            @if($project->administrator_notes)
                <div class="bg-white rounded-lg shadow-sm border">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Administrator Notes</h3>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none">
                            {!! nl2br(e($project->administrator_notes)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Developer Notes -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Your Notes</h3>
                </div>
                <div class="p-6">
                    @if($project->developer_notes)
                        <div class="prose max-w-none mb-4">
                            {!! nl2br(e($project->developer_notes)) !!}
                        </div>
                    @endif
                    <button onclick="editDeveloperNotes()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-edit mr-2"></i>{{ $project->developer_notes ? 'Edit Notes' : 'Add Notes' }}
                    </button>
                </div>
            </div>

            <!-- Project Documents -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Project Documents</h3>
                </div>
                <div class="p-6">
                    @forelse($project->documents as $document)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg mb-3">
                            <div class="flex items-center">
                                <i class="fas fa-file text-gray-400 mr-3"></i>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $document->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $document->mime_type }} • {{ $document->file_size ? number_format($document->file_size / 1024, 1) . ' KB' : 'Unknown size' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('project-documents.download', $document) }}" class="text-blue-600 hover:text-blue-500">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No documents uploaded yet.</p>
                    @endforelse
                    
                    <button onclick="uploadDocument()" class="mt-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-upload mr-2"></i>Upload Document
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Project Info -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Project Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <div class="text-sm font-medium text-gray-500">Deadline</div>
                        <div class="text-lg font-semibold text-gray-900">
                            {{ $project->delivery_date ? \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') : 'No deadline' }}
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-500">Assigned</div>
                        <div class="text-lg font-semibold text-gray-900">
                            {{ $project->assigned_at ? \Carbon\Carbon::parse($project->assigned_at)->format('M d, Y') : 'Not assigned' }}
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-500">Started</div>
                        <div class="text-lg font-semibold text-gray-900">
                            {{ $project->started_at ? \Carbon\Carbon::parse($project->started_at)->format('M d, Y') : 'Not started' }}
                        </div>
                    </div>
                    
                    <div>
                        <div class="text-sm font-medium text-gray-500">Last Activity</div>
                        <div class="text-lg font-semibold text-gray-900">
                            {{ $project->last_activity_at ? \Carbon\Carbon::parse($project->last_activity_at)->diffForHumans() : 'No activity' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Logging -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Time Logging</h3>
                </div>
                <div class="p-6">
                    <button onclick="logTime()" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors mb-4">
                        <i class="fas fa-clock mr-2"></i>Log Time
                    </button>
                    
                    <div class="space-y-2">
                        @forelse($project->timeLogs as $log)
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                <div>
                                    <div class="text-sm font-medium">{{ $log->hours_spent }} hours</div>
                                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log->work_date)->format('M d, Y') }}</div>
                                </div>
                                <div class="text-sm text-gray-500">{{ $log->description }}</div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No time logged yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <button onclick="updateProjectStatus()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-edit mr-2"></i>Update Status
                    </button>
                    <button onclick="logTime()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-clock mr-2"></i>Log Time
                    </button>
                    <button onclick="uploadDocument()" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-upload mr-2"></i>Upload Document
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Project Status</h3>
                <form id="statusForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="projectStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="pending" {{ $project->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $project->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="on_hold" {{ $project->status === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Progress (%)</label>
                        <input type="number" id="projectProgress" min="0" max="100" value="{{ $project->progress ?? 0 }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea id="projectNotes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Add any notes about the project...">{{ $project->developer_notes }}</textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeStatusModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
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

