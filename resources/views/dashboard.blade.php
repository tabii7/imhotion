@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <!-- Client Area -->
    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl p-5 text-white">
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

            <!-- Active Projects Section -->
            <div class="mb-8">
                <h2 class="text-white text-xl font-semibold mb-6 tracking-wide">
                    Active Projects
                </h2>

                <div class="space-y-3">
                    @forelse($active ?? [] as $project)
                        @include('dashboard.project-item', ['project' => $project, 'section' => 'active'])
                    @empty
                        <div class="text-center py-8">
                            <div class="text-sidebar-text text-lg mb-2">There are currently no active projects.</div>
                                        </div>
                    @endforelse
                                        </div>
                                    </div>

            <!-- Finalized Projects Section -->
            <div class="mb-8">
                <h2 class="text-white text-xl font-semibold mb-6 tracking-wide">
                    Finalized Projects
                </h2>

                <div class="space-y-3">
                    @forelse($finalized ?? [] as $project)
                        @include('dashboard.project-item', ['project' => $project, 'section' => 'finalized'])
                                @empty
                        <div class="text-center py-8">
                            <div class="text-sidebar-text text-lg mb-2">There are currently no finalized projects.</div>
                        </div>
                                @endforelse
                </div>
            </div>
        </div>
                            </div>

    <!-- Project Modal -->
    <div id="projectModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
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
        // Get project data from the page (since we already have it loaded)
        const projectElement = document.querySelector(`[data-project-id="${projectId}"]`);
        if (!projectElement) {
            console.error('Project element not found');
                    return;
                }

        const projectData = {
            id: projectId,
            title: projectElement.dataset.projectTitle,
            topic: projectElement.dataset.projectTopic,
            status: projectElement.dataset.projectStatus,
            delivery_date: projectElement.dataset.projectDeliveryDate,
            days_used: projectElement.dataset.projectDaysUsed,
            day_budget: projectElement.dataset.projectDayBudget,
            notes: projectElement.dataset.projectNotes,
            start_date: projectElement.dataset.projectStartDate,
            end_date: projectElement.dataset.projectEndDate,
            progress: projectElement.dataset.projectProgress
        };

        document.getElementById('projectModalContent').innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
                        <div class="text-gray-900 font-medium">${projectData.title || 'N/A'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                        <div class="text-gray-900">${projectData.topic || 'No topic specified'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <div class="text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(projectData.status)}">
                                ${getStatusLabel(projectData.status)}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Progress</label>
                        <div class="text-gray-900">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: ${projectData.progress || 0}%"></div>
                            </div>
                            <span class="text-sm text-gray-600">${projectData.progress || 0}%</span>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <div class="text-gray-900">${projectData.start_date ? new Date(projectData.start_date).toLocaleDateString() : 'Not set'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <div class="text-gray-900">${projectData.end_date ? new Date(projectData.end_date).toLocaleDateString() : 'Not set'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
                        <div class="text-gray-900">${projectData.delivery_date ? new Date(projectData.delivery_date).toLocaleDateString() : 'TBD'}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Days Used</label>
                        <div class="text-gray-900">${projectData.days_used || 0} days</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Day Budget</label>
                        <div class="text-gray-900">${projectData.day_budget ? '€' + projectData.day_budget : 'Not set'}</div>
                    </div>
                </div>
            </div>
            
            ${projectData.notes ? `
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <div class="text-gray-900 bg-gray-50 p-3 rounded-lg">${projectData.notes}</div>
                </div>
            ` : ''}
        `;
        
        document.getElementById('projectModal').classList.remove('hidden');
        document.getElementById('projectModal').classList.add('flex');
    }

    function getStatusLabel(status) {
        const labels = {
            'pending': 'Pending',
            'in_progress': 'In Progress',
            'completed': 'Completed',
            'on_hold': 'On Hold',
            'finalized': 'Finalized',
            'cancelled': 'Cancelled'
        };
        return labels[status] || status;
    }

    function getStatusColor(status) {
        const colors = {
            'pending': 'bg-gray-100 text-gray-800',
            'in_progress': 'bg-yellow-100 text-yellow-800',
            'completed': 'bg-green-100 text-green-800',
            'on_hold': 'bg-orange-100 text-orange-800',
            'finalized': 'bg-blue-100 text-blue-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }

    function closeProjectModal() {
        document.getElementById('projectModal').classList.add('hidden');
        document.getElementById('projectModal').classList.remove('flex');
    }

    function downloadFiles(projectId) {
        // Create a form to download project files
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/projects/${projectId}/download`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
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