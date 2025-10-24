@extends('layouts.developer')

@section('page-title', 'My Projects')

@section('content')

    <!-- Filters -->
    <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-white mb-3">Status</label>
                <select name="status" class="w-full bg-gray-800/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-white mb-3">Priority</label>
                <select name="priority" class="w-full bg-gray-800/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-white mb-3">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..."
                    class="w-full bg-gray-800/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @forelse($projects as $project)
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                <div class="p-8">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">{{ $project->name }}</h3>
                            <p class="text-gray-300">{{ $project->topic }}</p>
                        </div>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium shadow-lg
                            @if($project->status === 'completed') bg-gradient-to-r from-green-500 to-emerald-500 text-white
                            @elseif($project->status === 'in_progress') bg-gradient-to-r from-blue-500 to-cyan-500 text-white
                            @elseif($project->status === 'pending') bg-gradient-to-r from-yellow-500 to-amber-500 text-white
                            @elseif($project->status === 'on_hold') bg-gradient-to-r from-gray-500 to-gray-600 text-white
                            @else bg-gradient-to-r from-red-500 to-rose-500 text-white @endif">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>

                    <!-- Project Details -->
                    <div class="space-y-4 mb-6">
                        @if($project->client_requirements)
                            <div>
                                <div class="text-sm font-medium text-blue-300 mb-2">Requirements</div>
                                <div class="text-sm text-gray-300 line-clamp-2">{{ Str::limit($project->client_requirements, 150) }}</div>
                            </div>
                        @endif

                        @if($project->administrator_notes)
                            <div>
                                <div class="text-sm font-medium text-purple-300 mb-2">Admin Notes</div>
                                <div class="text-sm text-gray-300 line-clamp-2">{{ Str::limit($project->administrator_notes, 150) }}</div>
                            </div>
                        @endif

                        @if($project->developer_notes)
                            <div>
                                <div class="text-sm font-medium text-green-300 mb-2">Your Notes</div>
                                <div class="text-sm text-gray-300 line-clamp-2">{{ Str::limit($project->developer_notes, 150) }}</div>
                            </div>
                        @endif

                        <!-- Recent Document Uploads -->
                        @if($project->documents && $project->documents->count() > 0)
                            <div>
                                <div class="text-sm font-medium text-purple-300 mb-2">Recent Documents</div>
                                <div class="space-y-1">
                                    @foreach($project->documents as $index => $document)
                                        <div class="flex items-center justify-between text-xs text-gray-300 hover:bg-gray-700/30 rounded-lg p-2 transition-colors {{ $index >= 3 ? 'hidden more-docs-' . $project->id : '' }}">
                                            <div class="flex items-center flex-1 min-w-0">
                                                <i class="fas fa-file text-purple-400 mr-2 flex-shrink-0"></i>
                                                <span class="truncate">{{ $document->name }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2 flex-shrink-0">
                                                <span class="text-gray-400">{{ $document->created_at->format('M d') }}</span>
                                                <a href="{{ route('developer.project-documents.download', $document) }}" class="text-blue-400 hover:text-blue-300 transition-colors" title="Download">
                                                    <i class="fas fa-download text-xs"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($project->documents->count() > 3)
                                        <div class="text-xs text-center py-1">
                                            <button onclick="toggleMoreDocuments({{ $project->id }})" class="text-blue-400 hover:text-blue-300 transition-colors cursor-pointer" id="toggle-btn-{{ $project->id }}">
                                                <span id="toggle-text-{{ $project->id }}">+{{ $project->documents->count() - 3 }} more documents</span>
                                                <i class="fas fa-chevron-down ml-1" id="toggle-icon-{{ $project->id }}"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    @if($project->progress)
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-medium text-white">Progress</span>
                                <span class="text-sm text-gray-300 font-semibold">{{ $project->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-700/50 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-500" style="width: {{ $project->progress }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Project Info -->
                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <div class="text-gray-400 mb-1">Deadline</div>
                            <div class="font-medium text-white">
                                {{ $project->delivery_date ? \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') : 'No deadline' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-gray-400 mb-1">Priority</div>
                            <div class="font-medium text-white capitalize">{{ $project->priority ?? 'Not set' }}</div>
                        </div>
                        <div>
                            <div class="text-gray-400 mb-1">Assigned</div>
                            <div class="font-medium text-white">
                                {{ $project->assigned_at ? \Carbon\Carbon::parse($project->assigned_at)->format('M d, Y') : 'Not assigned' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-gray-400 mb-1">Last Activity</div>
                            <div class="font-medium text-white">
                                {{ $project->last_activity_at ? \Carbon\Carbon::parse($project->last_activity_at)->diffForHumans() : 'No activity' }}
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-3">
                        <a href="{{ route('developer.projects.show', $project) }}" class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-center px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-eye mr-2"></i>View Details
                        </a>
                        @if($project->status !== 'completed')
                            <button onclick="updateProjectStatus({{ $project->id }})" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-center px-6 py-3 rounded-xl text-sm font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-edit mr-2"></i>Update
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <div class="w-24 h-24 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-folder-open text-white text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-4">No projects found</h3>
                <p class="text-gray-400 text-lg mb-8">You don't have any projects assigned yet.</p>
                <a href="{{ route('developer.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
        <div class="mt-8">
            {{ $projects->links() }}
        </div>
    @endif


<!-- Update Status Modal -->
<div id="statusModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800/95 backdrop-blur-sm border border-gray-700/50 rounded-2xl shadow-2xl max-w-lg w-full">
            <div class="p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Update Project</h3>
                <form id="statusForm" enctype="multipart/form-data">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-white mb-3">Status</label>
                        <select id="projectStatus" class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="on_hold">On Hold</option>
                            <option value="finalized">Finalized</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-white mb-3">Progress (%)</label>
                        <input type="number" id="projectProgress" min="0" max="100" class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-white mb-3">Update Notes</label>
                        <textarea id="projectNotes" rows="3" class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Describe what you've worked on, challenges faced, or next steps..."></textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-white mb-3">Upload Document (Optional)</label>
                        <div class="relative">
                            <input type="file" id="projectDocument" class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif">
                            <div class="text-xs text-gray-400 mt-2">Max file size: 10MB. Supported formats: PDF, DOC, DOCX, TXT, JPG, PNG, GIF</div>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="closeStatusModal()" class="px-6 py-3 border border-gray-600/50 rounded-xl text-gray-300 hover:bg-gray-700/50 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                            Update Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
let currentProjectId = null;

function updateProjectStatus(projectId) {
    currentProjectId = projectId;
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    currentProjectId = null;
}

function toggleMoreDocuments(projectId) {
    const moreDocs = document.querySelectorAll('.more-docs-' + projectId);
    const toggleText = document.getElementById('toggle-text-' + projectId);
    const toggleIcon = document.getElementById('toggle-icon-' + projectId);
    
    const isHidden = moreDocs[0].classList.contains('hidden');
    
    moreDocs.forEach(doc => {
        if (isHidden) {
            doc.classList.remove('hidden');
        } else {
            doc.classList.add('hidden');
        }
    });
    
    if (isHidden) {
        toggleText.textContent = 'Show less';
        toggleIcon.classList.remove('fa-chevron-down');
        toggleIcon.classList.add('fa-chevron-up');
    } else {
        const totalDocs = document.querySelectorAll('.more-docs-' + projectId).length + 3;
        const moreCount = totalDocs - 3;
        toggleText.textContent = '+' + moreCount + ' more documents';
        toggleIcon.classList.remove('fa-chevron-up');
        toggleIcon.classList.add('fa-chevron-down');
    }
}


document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('status', document.getElementById('projectStatus').value);
    formData.append('progress', document.getElementById('projectProgress').value);
    formData.append('developer_notes', document.getElementById('projectNotes').value);
    
    // Add file if selected
    const fileInput = document.getElementById('projectDocument');
    if (fileInput.files.length > 0) {
        formData.append('file', fileInput.files[0]);
        formData.append('document_description', document.getElementById('projectNotes').value || 'Project update document');
    }
    
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Debug logging removed for performance
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Updating...';
    submitBtn.disabled = true;
    
    fetch(`/developer/projects/${currentProjectId}/update-status`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                throw new Error('Server returned non-JSON response');
            });
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message in modal
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            form.innerHTML = `
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Success!</h3>
                    <p class="text-gray-300 mb-6">Project updated successfully!</p>
                    <button onclick="closeStatusModal(); location.reload();" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-check mr-2"></i>Continue
                    </button>
                </div>
            `;
        } else {
            alert('Error updating project: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Error updating project: ' + error.message);
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
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
