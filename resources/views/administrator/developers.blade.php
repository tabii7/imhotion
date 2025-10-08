@extends('layouts.administrator')

@section('page-title', 'Developer Management')
@section('page-subtitle', 'View and manage all developers')

@section('content')
<!-- Developers List -->
<div class="stats-card">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-white">All Developers</h3>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" placeholder="Search developers..." class="bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
            <select class="bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>
    </div>
    
    <div class="space-y-4">
        @forelse($developers as $developer)
            <div class="project-card" data-developer-id="{{ $developer->id }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="project-title">{{ $developer->name }}</h4>
                            <div class="flex items-center space-x-3">
                                <span class="status-badge {{ $developer->is_available ? 'status-in-progress' : 'status-cancelled' }}">
                                    {{ $developer->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                                <span class="status-badge bg-blue-500 text-white">
                                    {{ ucfirst($developer->experience_level ?? 'Developer') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-400 mb-4">
                            <span class="flex items-center mr-6">
                                <i class="fas fa-envelope mr-2"></i>
                                {{ $developer->email }}
                            </span>
                            @if($developer->specialization)
                                <span class="flex items-center mr-6">
                                    <i class="fas fa-tag mr-2"></i>
                                    {{ $developer->specialization->name }}
                                </span>
                            @endif
                            <span class="flex items-center">
                                <i class="fas fa-tasks mr-2"></i>
                                {{ $developer->assigned_projects_count ?? 0 }} projects
                            </span>
                        </div>

                        @if($developer->skills && count($developer->skills) > 0)
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($developer->skills, 0, 5) as $skill)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                    @if(count($developer->skills) > 5)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                            +{{ count($developer->skills) - 5 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($developer->bio)
                            <p class="project-description">{{ Str::limit($developer->bio, 150) }}</p>
                        @endif

                        <!-- Assigned Projects -->
                        @if($developer->assignedProjects && $developer->assignedProjects->count() > 0)
                            <div class="mb-4">
                                <h5 class="text-sm font-medium text-gray-300 mb-2">Current Projects:</h5>
                                <div class="space-y-2">
                                    @foreach($developer->assignedProjects->take(3) as $project)
                                        <div class="flex items-center justify-between bg-gray-800/50 rounded-lg p-3">
                                            <div>
                                                <div class="text-white font-medium text-sm">{{ $project->title }}</div>
                                                <div class="text-gray-400 text-xs">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</div>
                                            </div>
                                            @if($project->progress)
                                                <div class="text-right">
                                                    <div class="text-white text-sm font-semibold">{{ $project->progress }}%</div>
                                                    <div class="w-16 bg-gray-700/50 rounded-full h-2 mt-1">
                                                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full" style="width: {{ $project->progress }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    @if($developer->assignedProjects->count() > 3)
                                        <div class="text-gray-400 text-xs text-center">
                                            +{{ $developer->assignedProjects->count() - 3 }} more projects
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="ml-6 flex-shrink-0 flex space-x-3">
                        <button onclick="viewDeveloper({{ $developer->id }})" class="btn btn-secondary">
                            <i class="fas fa-eye"></i>
                            View Details
                        </button>
                        <button onclick="toggleAvailability({{ $developer->id }}, {{ $developer->is_available ? 'false' : 'true' }})" class="btn {{ $developer->is_available ? 'btn-danger' : 'btn-success' }}">
                            <i class="fas fa-{{ $developer->is_available ? 'pause' : 'play' }}"></i>
                            {{ $developer->is_available ? 'Make Unavailable' : 'Make Available' }}
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-users text-white text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">No developers found</h3>
                <p class="text-gray-400 text-lg">No developers have been registered yet.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Pagination -->
@if($developers->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $developers->links() }}
    </div>
@endif

<!-- Developer Details Modal -->
<div id="developerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-2xl shadow-xl max-w-2xl w-full">
            <div class="px-8 py-6 border-b border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-2xl font-bold text-white">Developer Details</h3>
                    <button onclick="closeDeveloperModal()" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-8" id="developerDetails">
                <!-- Developer details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function viewDeveloper(developerId) {
    // Find the developer data from the current page
    const developerElement = document.querySelector(`[data-developer-id="${developerId}"]`);
    if (!developerElement) {
        alert('Developer not found');
        return;
    }
    
    // Extract developer data from the page
    const name = developerElement.querySelector('.project-title').textContent;
    const email = developerElement.querySelector('.fa-envelope').parentElement.textContent.trim();
    const experienceLevel = developerElement.querySelector('.bg-blue-500').textContent;
    const isAvailable = developerElement.querySelector('.status-in-progress') !== null;
    const bio = developerElement.querySelector('.project-description')?.textContent || '';
    const skills = Array.from(developerElement.querySelectorAll('.bg-gray-700.text-gray-300')).map(el => el.textContent);
    
    document.getElementById('developerDetails').innerHTML = `
        <div class="space-y-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    ${name.charAt(0)}
                </div>
                <div>
                    <h4 class="text-xl font-bold text-white">${name}</h4>
                    <p class="text-gray-400">${email}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-300">Experience Level</label>
                    <p class="text-white">${experienceLevel}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-300">Availability</label>
                    <p class="text-white">${isAvailable ? 'Available' : 'Unavailable'}</p>
                </div>
            </div>
            
            ${bio ? `
                <div>
                    <label class="text-sm font-medium text-gray-300">Bio</label>
                    <p class="text-white">${bio}</p>
                </div>
            ` : ''}
            
            ${skills.length > 0 ? `
                <div>
                    <label class="text-sm font-medium text-gray-300">Skills</label>
                    <div class="flex flex-wrap gap-2 mt-2">
                        ${skills.map(skill => `
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                ${skill}
                            </span>
                        `).join('')}
                    </div>
                </div>
            ` : ''}
        </div>
    `;
    
    // Show modal
    document.getElementById('developerModal').classList.remove('hidden');
}

function closeDeveloperModal() {
    document.getElementById('developerModal').classList.add('hidden');
}

function toggleAvailability(developerId, newStatus) {
    // Create a form and submit it to the correct route
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/developers/${developerId}/toggle-availability`;
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);
    
    // Add to DOM, submit, and remove
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endsection