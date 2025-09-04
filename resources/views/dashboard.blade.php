@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Mini Cart Component -->
    @include('components.mini-cart')

    <!-- Client Area -->
    <div class="bg-sidebar-bg rounded-xl p-5 text-white">
        <div class="text-white font-sans">
            <!-- Stats Grid -->
            <div class="grid grid-cols-3 gap-3 mx-auto mb-5 max-w-lg">
                <div class="bg-sidebar-active border border-blue-300 rounded-xl px-3 py-4 text-center min-h-[70px] flex flex-col justify-center transition-all duration-200 cursor-pointer hover:scale-105 hover:bg-blue-900/50">
                    <div class="text-white text-xs font-medium mb-2 tracking-wide">
                        Active Projects
                    </div>
                    <div class="text-white text-sm font-semibold px-3 py-1 border border-blue-300 rounded-xl inline-block min-w-[40px] transition-colors duration-200 bg-blue-900/50 hover:bg-blue-800/50">
                        {{ $counts['active'] ?? 0 }}
                    </div>
                </div>
                
                <div class="bg-sidebar-active border border-blue-300 rounded-xl px-3 py-4 text-center min-h-[70px] flex flex-col justify-center transition-all duration-200 cursor-pointer hover:scale-105 hover:bg-blue-900/50">
                    <div class="text-white text-xs font-medium mb-2 tracking-wide">
                        Finalized
                    </div>
                    <div class="text-white text-sm font-semibold px-3 py-1 border border-blue-300 rounded-xl inline-block min-w-[40px] transition-colors duration-200 bg-slate-700 hover:bg-slate-600">
                        {{ $counts['finalized'] ?? 0 }}
                    </div>
                </div>
                
                <div class="bg-sidebar-active border border-blue-300 rounded-xl px-3 py-4 text-center min-h-[70px] flex flex-col justify-center transition-all duration-200 cursor-pointer hover:scale-105 hover:bg-blue-900/50">
                    <div class="text-white text-xs font-medium mb-2 tracking-wide">
                        Balance
                    </div>
                    <div class="text-white text-sm font-semibold px-3 py-1 border border-blue-300 rounded-xl inline-block min-w-[40px] transition-colors duration-200 bg-blue-700 hover:bg-blue-600">
                        {{ $counts['balance'] ?? 0 }} days
                    </div>
                </div>
            </div>

            <!-- Section Title -->
            <h2 class="text-white text-xl font-semibold mb-6 tracking-wide">
                Your Projects
            </h2>

            <!-- Projects List -->
            <div class="space-y-3">
                @forelse($active ?? [] as $project)
                    <div class="bg-sidebar-active border border-blue-300 rounded-2xl p-1.5 mb-3 flex items-center min-h-[65px] transition-all duration-200 cursor-pointer hover:bg-blue-900/30 hover:scale-105">
                        <!-- Project Number -->
                        <div class="w-12 h-8 bg-blue-600 text-white border border-blue-300 rounded-lg flex items-center justify-center font-bold text-sm flex-shrink-0 mr-5">
                            {{ $project->id }}
                        </div>
                        
                        <!-- Project Info -->
                        <div class="flex-1 min-w-0">
                            <div class="text-white text-base font-semibold mb-1 leading-tight">
                                {{ $project->title ?? 'Project ' . $project->id }}
                            </div>
                            <div class="text-sidebar-text text-sm font-normal leading-tight">
                                {{ $project->topic ?? 'No topic specified' }}
                            </div>
                        </div>
                        
                        <!-- Delivery Date -->
                        <div class="text-white text-sm font-medium mx-6 whitespace-nowrap flex-shrink-0">
                            {{ $project->delivery_date ? \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') : 'TBD' }}
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-2.5 flex-shrink-0">
                            <button onclick="openProjectModal({{ $project->id }})" 
                                    class="bg-brand-primary text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-blue-600 transition-colors duration-200">
                                View
                            </button>
                            @if($project->status !== 'finalized')
                                <button onclick="markAsFinalized({{ $project->id }})" 
                                        class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-green-700 transition-colors duration-200">
                                    Finalize
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="text-sidebar-text text-lg mb-2">No projects yet</div>
                        <div class="text-sidebar-text text-sm">Your projects will appear here once they're created.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Project Modal -->
    <div id="projectModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">Project Details</h3>
                    <button onclick="closeProjectModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                <div id="projectModalContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- File Lightbox -->
    <div id="fileLightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-[90vh] w-full">
            <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div id="lightboxContent" class="bg-white rounded-lg overflow-hidden">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openProjectModal(projectId) {
        // Fetch project details and populate modal
        fetch(`/api/projects/${projectId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('projectModalContent').innerHTML = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
                            <div class="text-gray-900">${data.title || 'N/A'}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                            <div class="text-gray-900">${data.topic || 'No topic specified'}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="text-gray-900">${data.status || 'Active'}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
                            <div class="text-gray-900">${data.delivery_date ? new Date(data.delivery_date).toLocaleDateString() : 'TBD'}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Days Used</label>
                            <div class="text-gray-900">${data.days_used || 0} days</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Day Budget</label>
                            <div class="text-gray-900">${data.day_budget || 'Not set'}</div>
                        </div>
                    </div>
                `;
                document.getElementById('projectModal').classList.remove('hidden');
                document.getElementById('projectModal').classList.add('flex');
            })
            .catch(error => {
                console.error('Error fetching project details:', error);
                document.getElementById('projectModalContent').innerHTML = '<div class="text-red-600">Error loading project details.</div>';
                document.getElementById('projectModal').classList.remove('hidden');
                document.getElementById('projectModal').classList.add('flex');
            });
    }

    function closeProjectModal() {
        document.getElementById('projectModal').classList.add('hidden');
        document.getElementById('projectModal').classList.remove('flex');
    }

    function markAsFinalized(projectId) {
        if (confirm('Are you sure you want to mark this project as finalized?')) {
            fetch(`/api/projects/${projectId}/finalize`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error finalizing project: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error finalizing project');
            });
        }
    }

    function openLightbox(fileUrl, fileName) {
        const extension = fileName.split('.').pop().toLowerCase();
        let content = '';
        
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
            content = `<img src="${fileUrl}" alt="${fileName}" class="w-full h-auto max-h-[80vh] object-contain">`;
        } else if (['mp4', 'webm', 'ogg'].includes(extension)) {
            content = `<video controls class="w-full h-auto max-h-[80vh]"><source src="${fileUrl}" type="video/${extension}"></video>`;
        } else if (['pdf'].includes(extension)) {
            content = `<iframe src="${fileUrl}" class="w-full h-[80vh] border-0"></iframe>`;
        } else {
            content = `<div class="p-8 text-center"><p class="text-gray-600 mb-4">Preview not available for this file type.</p><a href="${fileUrl}" download="${fileName}" class="bg-brand-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">Download ${fileName}</a></div>`;
        }
        
        document.getElementById('lightboxContent').innerHTML = content;
        document.getElementById('fileLightbox').classList.remove('hidden');
        document.getElementById('fileLightbox').classList.add('flex');
    }

    function closeLightbox() {
        document.getElementById('fileLightbox').classList.add('hidden');
        document.getElementById('fileLightbox').classList.remove('flex');
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        if (event.target.id === 'projectModal') {
            closeProjectModal();
        }
        if (event.target.id === 'fileLightbox') {
            closeLightbox();
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (!document.getElementById('fileLightbox').classList.contains('hidden')) {
                closeLightbox();
            } else if (!document.getElementById('projectModal').classList.contains('hidden')) {
                closeProjectModal();
            }
        }
    });
</script>
@endsection