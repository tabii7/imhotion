@extends('layouts.developer')

@section('page-title', 'Project Progress')
@section('page-subtitle', 'Track your work and upload files for ' . $project->title)

@section('content')
<!-- Project Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ $project->overall_progress }}%</div>
                <div class="stats-label">Overall Progress</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-chart-line text-white text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ number_format($project->total_hours_worked, 1) }}</div>
                <div class="stats-label">Hours Worked</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-clock text-white text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ number_format($project->hours_remaining, 1) }}</div>
                <div class="stats-label">Hours Remaining</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-amber-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-hourglass-half text-white text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stats-card">
        <div class="flex items-center justify-between">
            <div>
                <div class="stats-number">{{ $project->total_files }}</div>
                <div class="stats-label">Files Uploaded</div>
            </div>
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-file-upload text-white text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Progress Tracking Actions -->
<div class="flex flex-wrap gap-4 mb-8">
    <button onclick="openProgressModal()" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i>
        Add Progress Entry
    </button>
    <button onclick="openFileUploadModal()" class="btn btn-secondary">
        <i class="fas fa-upload mr-2"></i>
        Upload Files
    </button>
    <a href="{{ route('developer.projects.show', $project) }}" class="btn btn-outline">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Project
    </a>
</div>

<!-- Progress Entries -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Progress -->
    <div class="stats-card">
        <h3 class="text-xl font-bold text-white mb-6">Recent Progress</h3>
        <div class="space-y-4">
            @forelse($progress as $entry)
                <div class="progress-entry">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-day text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-semibold">{{ $entry->work_date->format('M d, Y') }}</h4>
                                <p class="text-gray-400 text-sm">{{ $entry->formatted_hours }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="status-badge status-{{ $entry->status_color }}">
                                {{ ucfirst(str_replace('_', ' ', $entry->status)) }}
                            </span>
                            <span class="text-blue-400 font-semibold">{{ $entry->progress_percentage }}%</span>
                        </div>
                    </div>
                    
                    <p class="text-gray-300 text-sm mb-3">{{ Str::limit($entry->description, 150) }}</p>
                    
                    @if($entry->tasks_completed && count($entry->tasks_completed) > 0)
                        <div class="mb-3">
                            <h5 class="text-green-400 text-sm font-semibold mb-1">Completed Tasks:</h5>
                            <ul class="text-gray-300 text-sm space-y-1">
                                @foreach($entry->tasks_completed as $task)
                                    <li class="flex items-center">
                                        <i class="fas fa-check text-green-400 mr-2 text-xs"></i>
                                        {{ $task }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if($entry->files && $entry->files->count() > 0)
                        <div class="mb-3">
                            <h5 class="text-blue-400 text-sm font-semibold mb-1">Files:</h5>
                            <div class="flex flex-wrap gap-2">
                                @foreach($entry->files as $file)
                                    <a href="{{ $file->file_url }}" target="_blank" class="text-blue-300 hover:text-blue-200 text-sm">
                                        <i class="fas fa-file mr-1"></i>
                                        {{ $file->original_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('developer.progress.show', [$project, $entry]) }}" class="btn btn-sm btn-outline">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('developer.progress.edit', [$project, $entry]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-gray-400 text-2xl"></i>
                    </div>
                    <h4 class="text-white font-semibold mb-2">No Progress Entries</h4>
                    <p class="text-gray-400">Start tracking your work by adding your first progress entry.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Files & Time Tracking -->
    <div class="space-y-6">
        <!-- Recent Files -->
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Recent Files</h3>
            <div class="space-y-3">
                @forelse($files->take(5) as $file)
                    <div class="flex items-center justify-between p-3 bg-gray-800/50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-600 rounded flex items-center justify-center">
                                <i class="{{ $file->file_icon }} text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-white text-sm font-medium">{{ $file->original_name }}</h4>
                                <p class="text-gray-400 text-xs">{{ $file->formatted_size }} • {{ $file->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <a href="{{ $file->file_url }}" target="_blank" class="text-blue-400 hover:text-blue-300">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <i class="fas fa-file-upload text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-400">No files uploaded yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Time Tracking -->
        <div class="stats-card">
            <h3 class="text-xl font-bold text-white mb-6">Time Tracking</h3>
            <div class="space-y-3">
                @forelse($timeTracking->take(5) as $track)
                    <div class="flex items-center justify-between p-3 bg-gray-800/50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-600 rounded flex items-center justify-center">
                                <i class="{{ $track->activity_type_icon }} text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-white text-sm font-medium">{{ ucfirst($track->activity_type) }}</h4>
                                <p class="text-gray-400 text-xs">{{ $track->tracking_date->format('M d, Y') }} • {{ $track->duration }}</p>
                            </div>
                        </div>
                        <span class="text-green-400 font-semibold">{{ $track->total_hours }}h</span>
                    </div>
                @empty
                    <div class="text-center py-6">
                        <i class="fas fa-clock text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-400">No time tracking entries yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div id="progressModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-white">Add Progress Entry</h3>
            <button onclick="closeProgressModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('developer.progress.store', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Work Date</label>
                        <input type="date" name="work_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Hours Worked</label>
                        <input type="number" name="hours_worked" class="form-input" step="0.1" min="0.1" max="24" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Progress Percentage</label>
                        <input type="range" name="progress_percentage" class="form-range" min="0" max="100" value="0" oninput="this.nextElementSibling.textContent = this.value + '%'">
                        <span class="text-blue-400 font-semibold">0%</span>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="blocked">Blocked</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="4" placeholder="Describe what you worked on today..." required></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Completed Tasks (one per line)</label>
                        <textarea name="tasks_completed[]" class="form-textarea" rows="3" placeholder="Task 1&#10;Task 2&#10;Task 3"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Challenges Faced (one per line)</label>
                        <textarea name="challenges_faced[]" class="form-textarea" rows="3" placeholder="Challenge 1&#10;Challenge 2"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Next Steps (one per line)</label>
                        <textarea name="next_steps[]" class="form-textarea" rows="3" placeholder="Next step 1&#10;Next step 2"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Upload Files (optional)</label>
                        <input type="file" name="files[]" class="form-input" multiple accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar">
                        <p class="text-gray-400 text-sm mt-1">Maximum 10MB per file. Supported formats: PDF, DOC, DOCX, TXT, JPG, PNG, GIF, ZIP, RAR</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeProgressModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Progress</button>
            </div>
        </form>
    </div>
</div>

<!-- File Upload Modal -->
<div id="fileUploadModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="text-xl font-bold text-white">Upload Files</h3>
            <button onclick="closeFileUploadModal()" class="modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="fileUploadForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Select Files</label>
                        <input type="file" name="file" class="form-input" required>
                        <p class="text-gray-400 text-sm mt-1">Maximum 10MB per file</p>
                    </div>
                    <div>
                        <label class="form-label">Description (optional)</label>
                        <textarea name="description" class="form-textarea" rows="3" placeholder="Describe the files you're uploading..."></textarea>
                    </div>
                    <div>
                        <label class="form-checkbox">
                            <input type="checkbox" name="is_public" value="1">
                            <span class="checkmark"></span>
                            Make files visible to client
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeFileUploadModal()" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Upload Files</button>
            </div>
        </form>
    </div>
</div>

<script>
function openProgressModal() {
    document.getElementById('progressModal').classList.add('show');
}

function closeProgressModal() {
    document.getElementById('progressModal').classList.remove('show');
}

function openFileUploadModal() {
    document.getElementById('fileUploadModal').classList.add('show');
}

function closeFileUploadModal() {
    document.getElementById('fileUploadModal').classList.remove('show');
}

// Handle file upload form
document.getElementById('fileUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("developer.progress.upload", $project) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Upload failed: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Upload failed. Please try again.');
    });
});

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('show');
    }
});
</script>
@endsection


