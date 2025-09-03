@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Mini Cart Component -->
                    @include('components.mini-cart')

                    <!-- Client Area - Exact Match to Reference -->
                    <div style="background: #0a1428; border-radius: 12px; padding: 20px; color: #ffffff;">
                        <style>
                            .exact-client-area {
                                color: #ffffff;
                                font-family: system-ui, -apple-system, sans-serif;
                            }
                            .exact-stats-grid {
                                display: grid;
                                grid-template-columns: repeat(3, 1fr);
                                gap: 12px;
                                margin: 0 auto 20px auto;
                                max-width: 500px;
                            }
                            .exact-stat-card {
                                background: #001f4c;
                                border: 1px solid #7fa7e1;
                                border-radius: 12px;
                                padding: 16px 12px;
                                text-align: center;
                                min-height: 70px;
                                display: flex;
                                flex-direction: column;
                                justify-content: center;
                                transition: transform 0.2s ease, background-color 0.2s ease;
                                cursor: pointer;
                            }
                            .exact-stat-card:hover {
                                transform: scale(1.05);
                                background: #002a66;
                            }
                            .exact-stat-title {
                                color: #ffffff;
                                font-size: 13px;
                                font-weight: 500;
                                margin-bottom: 8px;
                                letter-spacing: 0.3px;
                            }
                            .exact-stat-value {
                                color: #ffffff;
                                font-size: 14px;
                                font-weight: 600;
                                padding: 4px 12px;
                                border: 1px solid #7fa7e1;
                                border-radius: 12px;
                                display: inline-block;
                                min-width: 40px;
                                transition: background-color 0.2s ease;
                            }
                            .exact-stat-value.active-finalized {
                                background: #19355e;
                            }
                            .exact-stat-value.balance {
                                background: #2e5182;
                            }
                            .exact-stat-value.active-finalized:hover {
                                background: #1c3d68;
                            }
                            .exact-stat-value.balance:hover {
                                background: #345a90;
                            }
                            .exact-section-title {
                                color: #ffffff;
                                font-size: 22px;
                                font-weight: 600;
                                margin-bottom: 25px;
                                letter-spacing: 0.3px;
                            }
                            .exact-project-item {
                                background: #001f4c;
                                border: 1px solid #7fa7e1;
                                border-radius: 15px;
                                padding: 3px 6px;
                                margin-bottom: 12px;
                                display: flex;
                                align-items: center;
                                min-height: 65px;
                                transition: all 2s ease;
                                cursor: pointer;
                            }
                            .exact-project-item:hover {
                                background: #33527a; /* 20% lighter than #001f4c */
                                transform: scale(1.05);
                            }
                            .exact-project-item.finalized {
                                background: #121e2f;
                            }
                            .exact-project-item.finalized:hover {
                                background: #394c66; /* 20% lighter than #121e2f */
                                transform: scale(1.05);
                            }
                            .exact-project-item.finalized .exact-project-number {
                                background: #2e3b4d; /* 10% lighter than #1a2a40 */
                            }
                            .exact-project-number {
                                width: 50px;
                                height: 32px;
                                background: #4a6b95; /* 10% lighter than #3d5a87 */
                                color: #ffffff;
                                border: 1px solid #7fa7e1;
                                border-radius: 8px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                font-weight: 700;
                                font-size: 14px;
                                flex-shrink: 0;
                                margin-right: 20px;
                            }
                            .exact-project-info {
                                flex: 1;
                                min-width: 0;
                            }
                            .exact-project-name {
                                color: #ffffff;
                                font-size: 16px;
                                font-weight: 600;
                                margin-bottom: 4px;
                                line-height: 1.3;
                            }
                            .exact-project-topic {
                                color: #8fa8cc;
                                font-size: 14px;
                                font-weight: 400;
                                line-height: 1.2;
                            }
                            .exact-project-delivery {
                                color: #ffffff;
                                font-size: 14px;
                                font-weight: 500;
                                margin: 0 25px;
                                white-space: nowrap;
                                flex-shrink: 0;
                            }
                            .exact-project-actions {
                                display: flex;
                                gap: 10px;
                                flex-shrink: 0;
                            }
                            .exact-status-btn {
                                padding: 8px 16px;
                                border: 1px solid #7fa7e1;
                                border-radius: 18px;
                                font-size: 13px;
                                font-weight: 600;
                                cursor: pointer;
                                transition: opacity 0.2s ease;
                                white-space: nowrap;
                            }
                            .exact-status-btn:hover {
                                opacity: 0.9;
                            }
                            .exact-status-completed {
                                background: #16a34a;
                                color: #ffffff;
                            }
                            .exact-status-in_progress {
                                background: #a16207;
                                color: #ffffff;
                            }
                            .exact-status-pending {
                                background: #d4931a;
                                color: #ffffff;
                            }
                            .exact-status-new {
                                background: #0891b2;
                                color: #ffffff;
                            }
                            .exact-status-finalized {
                                background: #6b7280;
                                color: #ffffff;
                            }
                            .exact-status-cancelled {
                                background: #dc2626;
                                color: #ffffff;
                            }
                            .exact-download-btn {
                                background: #3366cc;
                                color: #ffffff;
                                padding: 8px 16px;
                                border: 1px solid #7fa7e1;
                                border-radius: 18px;
                                font-size: 13px;
                                font-weight: 600;
                                cursor: pointer;
                                transition: opacity 0.2s ease;
                                white-space: nowrap;
                            }
                            .exact-download-btn:hover {
                                opacity: 0.9;
                            }
                            .exact-empty-message {
                                color: #8fa8cc;
                                font-style: italic;
                                font-size: 14px;
                                padding: 20px 0;
                                text-align: left;
                            }
                            .exact-projects-section {
                                margin-bottom: 45px;
                            }
                            @media (max-width: 768px) {
                                .exact-stats-grid {
                                    grid-template-columns: 1fr;
                                    gap: 15px;
                                }
                                .exact-project-item {
                                    flex-direction: column;
                                    text-align: center;
                                    padding: 20px;
                                }
                                .exact-project-info {
                                    margin: 15px 0;
                                    text-align: center;
                                }
                                .exact-project-delivery {
                                    margin: 10px 0;
                                    text-align: center;
                                }
                                .exact-project-actions {
                                    justify-content: center;
                                    flex-wrap: wrap;
                                }
                            }
                        </style>

                        <div class="exact-client-area">
                            <!-- Stats Grid -->
                            <div class="exact-stats-grid">
                                <div class="exact-stat-card">
                                    <div class="exact-stat-title">Active</div>
                                    <div class="exact-stat-value active-finalized">{{ $counts['active'] }}</div>
                                </div>
                                <div class="exact-stat-card">
                                    <div class="exact-stat-title">Balance</div>
                                    <div class="exact-stat-value balance">{{ $counts['balance'] }} Days</div>
                                </div>
                                <div class="exact-stat-card">
                                    <div class="exact-stat-title">Finalized</div>
                                    <div class="exact-stat-value active-finalized">{{ $counts['finalized'] }}</div>
                                </div>
                            </div>

                            <!-- Active Projects -->
                            <div class="exact-projects-section">
                                <h3 class="exact-section-title">Active Projects</h3>
                                @forelse($active as $index => $p)
                                    <div class="exact-project-item" onclick="openProjectModal({ id: {{ $p->id }}, title: {{ json_encode($p->title) }}, topic: {{ json_encode($p->topic ?? 'Topic') }}, status: {{ json_encode($p->status) }}, delivery_date: {{ json_encode($p->delivery_date ? $p->delivery_date->format('Y-m-d') : null) }}, start_date: {{ json_encode($p->start_date ? $p->start_date->format('Y-m-d') : null) }}, end_date: {{ json_encode($p->end_date ? $p->end_date->format('Y-m-d') : null) }}, day_budget: {{ $p->day_budget ?? 0 }}, days_used: {{ $p->days_used ?? 0 }}, notes: {{ json_encode($p->notes ?? '') }}, client_name: {{ json_encode($p->user ? $p->user->name : 'Unknown Client') }}, client_email: {{ json_encode($p->user ? $p->user->email : null) }} });" style="cursor: pointer;">
                                        <div class="exact-project-number">25{{ str_pad($p->id, 2, '0', STR_PAD_LEFT) }}</div>
                                        <div class="exact-project-info">
                                            <div class="exact-project-name">{{ e($p->title) }}</div>
                                            <div class="exact-project-topic">{{ $p->topic ? e($p->topic) : 'Topic' }}</div>
                                        </div>
                                        <div class="exact-project-delivery">
                                            {{ $p->delivery_date ? 'Delivery by ' . $p->delivery_date->format('j F') : 'Delivery by TBD' }}
                                        </div>
                                        <div class="exact-project-actions">
                                            @if($p->status === 'completed')
                                                <button class="exact-status-btn exact-status-completed">Completed</button>
                                            @elseif($p->status === 'in_progress')
                                                <button class="exact-status-btn exact-status-in_progress">In Progress</button>
                                            @elseif($p->status === 'pending')
                                                <button class="exact-status-btn exact-status-pending">Pending</button>
                                            @elseif($p->status === 'new')
                                                <button class="exact-status-btn exact-status-new">New</button>
                                            @else
                                                <button class="exact-status-btn exact-status-new">New</button>
                                            @endif
                                            <button class="exact-download-btn" onclick="event.stopPropagation(); openProjectModal({ id: {{ $p->id }}, title: {{ json_encode($p->title) }}, topic: {{ json_encode($p->topic ?? 'Topic') }}, status: {{ json_encode($p->status) }}, delivery_date: {{ json_encode($p->delivery_date ? $p->delivery_date->format('Y-m-d') : null) }}, start_date: {{ json_encode($p->start_date ? $p->start_date->format('Y-m-d') : null) }}, end_date: {{ json_encode($p->end_date ? $p->end_date->format('Y-m-d') : null) }}, day_budget: {{ $p->day_budget ?? 0 }}, days_used: {{ $p->days_used ?? 0 }}, notes: {{ json_encode($p->notes ?? '') }}, client_name: {{ json_encode($p->user ? $p->user->name : 'Unknown Client') }}, client_email: {{ json_encode($p->user ? $p->user->email : null) }} }); switchModalTab('files');">Download Files</button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="exact-empty-message">There are currently no active projects.</div>
                                @endforelse
                            </div>

                            <!-- Finalized Projects -->
                            <div class="exact-projects-section">
                                <h3 class="exact-section-title">Finalized Projects</h3>
                                @if($finalized->count() > 0)
                                    @foreach($finalized as $p)
                                        <div class="exact-project-item finalized" onclick="openProjectModal({ id: {{ $p->id }}, title: {{ json_encode($p->title) }}, topic: {{ json_encode($p->topic ?? 'Topic') }}, status: {{ json_encode($p->status) }}, delivery_date: {{ json_encode($p->delivery_date ? $p->delivery_date->format('Y-m-d') : null) }}, start_date: {{ json_encode($p->start_date ? $p->start_date->format('Y-m-d') : null) }}, end_date: {{ json_encode($p->end_date ? $p->end_date->format('Y-m-d') : null) }}, day_budget: {{ $p->day_budget ?? 0 }}, days_used: {{ $p->days_used ?? 0 }}, notes: {{ json_encode($p->notes ?? '') }}, client_name: {{ json_encode($p->user ? $p->user->name : 'Unknown Client') }}, client_email: {{ json_encode($p->user ? $p->user->email : null) }} });" style="cursor: pointer;">
                                            <div class="exact-project-number">25{{ str_pad($p->id, 2, '0', STR_PAD_LEFT) }}</div>
                                            <div class="exact-project-info">
                                                <div class="exact-project-name">{{ e($p->title) }}</div>
                                                <div class="exact-project-topic">{{ $p->topic ? e($p->topic) : 'Topic' }}</div>
                                            </div>
                                            <div class="exact-project-delivery">
                                                {{ $p->delivery_date ? 'Delivered on ' . $p->delivery_date->format('j F') : 'Delivered on TBD' }}
                                            </div>
                                            <div class="exact-project-actions">
                                                @if($p->status === 'finalized')
                                                    <button class="exact-status-btn exact-status-finalized">Finalized</button>
                                                @elseif($p->status === 'cancelled')
                                                    <button class="exact-status-btn exact-status-cancelled">Cancelled</button>
                                                @else
                                                    <button class="exact-status-btn exact-status-finalized">Finalized</button>
                                                @endif
                                                <button class="exact-download-btn" onclick="event.stopPropagation(); openProjectModal({ id: {{ $p->id }}, title: {{ json_encode($p->title) }}, topic: {{ json_encode($p->topic ?? 'Topic') }}, status: {{ json_encode($p->status) }}, delivery_date: {{ json_encode($p->delivery_date ? $p->delivery_date->format('Y-m-d') : null) }}, start_date: {{ json_encode($p->start_date ? $p->start_date->format('Y-m-d') : null) }}, end_date: {{ json_encode($p->end_date ? $p->end_date->format('Y-m-d') : null) }}, day_budget: {{ $p->day_budget ?? 0 }}, days_used: {{ $p->days_used ?? 0 }}, notes: {{ json_encode($p->notes ?? '') }}, client_name: {{ json_encode($p->user ? $p->user->name : 'Unknown Client') }}, client_email: {{ json_encode($p->user ? $p->user->email : null) }} }); switchModalTab('files');">Download Files</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="exact-empty-message">There are currently no finalized projects.</div>
                                @endif
                            </div>
                        </div>
                    </div>
@endsection

@section('scripts')
    <!-- Include Project Modal Component -->
    @include('components.project-modal')

    <script>
        // Project Modal Functions
        let currentProjectData = null;

        // Function to open project modal
        function openProjectModal(projectData) {
            console.log('openProjectModal called with:', projectData);

            try {
                currentProjectData = projectData;

                // Check if modal elements exist
                const modal = document.getElementById('projectModal');
                if (!modal) {
                    console.error('Modal element not found!');
                    alert('Modal not found - check if component is included');
                    return;
                }

                // Populate modal with project data
                const titleElement = document.getElementById('modalProjectTitle');
                if (titleElement) {
                    titleElement.textContent = projectData.title || 'Project Title';
                } else {
                    console.error('Modal title element not found!');
                }

                const numberElement = document.getElementById('modalProjectNumber');
                if (numberElement) {
                    numberElement.textContent = '25' + String(projectData.id).padStart(2, '0');
                }
                // also populate the header project number near the close button
                const numberHeaderEl = document.getElementById('modalProjectNumberHeader');
                if (numberHeaderEl) {
                    numberHeaderEl.textContent = '25' + String(projectData.id).padStart(2, '0');
                }

                const topicElement = document.getElementById('modalProjectTopic');
                if (topicElement) {
                    topicElement.textContent = projectData.topic || 'Topic';
                }
                // populate header topic value
                const topicHeaderValue = document.getElementById('modalProjectTopicHeaderValue');
                if (topicHeaderValue) {
                    topicHeaderValue.textContent = projectData.topic || '';
                }

                // Status
                const statusElement = document.getElementById('modalProjectStatus');
                if (statusElement) {
                    statusElement.textContent = formatStatus(projectData.status);
                    statusElement.className = 'project-modal-status ' + projectData.status;
                }

                // Delivery date
                const deliveryText = projectData.delivery_date ?
                    (projectData.status === 'finalized' ? 'Delivered on ' : 'Delivery by ') + formatDate(projectData.delivery_date) :
                    'Delivery TBD';
                const deliveryElement = document.getElementById('modalProjectDelivery');
                if (deliveryElement) {
                    deliveryElement.textContent = deliveryText;
                }

                // Client info
                const clientNameElement = document.getElementById('modalClientName');
                if (clientNameElement) {
                    clientNameElement.textContent = projectData.client_name || 'Client Name';
                }

                const clientEmailElement = document.getElementById('modalClientEmail');
                if (clientEmailElement) {
                    clientEmailElement.textContent = projectData.client_email ? '(' + projectData.client_email + ')' : '';
                }

                // Budget info
                const budget = projectData.day_budget || 0;
                const used = projectData.days_used || 0;
                const remaining = Math.max(0, budget - used);

                const budgetElement = document.getElementById('modalBudget');
                if (budgetElement) {
                    budgetElement.textContent = budget + ' days';
                }

                const remainingElement = document.getElementById('modalRemainingDays');
                if (remainingElement) {
                    remainingElement.textContent = remaining + ' remaining';
                }

                // Dates
                const startDateElement = document.getElementById('modalStartDate');
                if (startDateElement) {
                    startDateElement.textContent = projectData.start_date ? formatDate(projectData.start_date) : 'Not set';
                }
                // also set the small header start date display
                const startHeader = document.getElementById('modalStartDateHeader');
                if (startHeader) startHeader.textContent = projectData.start_date ? formatDate(projectData.start_date) : 'Not set';

                const endDateElement = document.getElementById('modalEndDate');
                if (endDateElement) {
                    endDateElement.textContent = projectData.end_date ? formatDate(projectData.end_date) : 'Not set';
                }
                const endHeader = document.getElementById('modalEndDateHeader');
                if (endHeader) endHeader.textContent = projectData.end_date ? formatDate(projectData.end_date) : 'Not set';

                const daysUsedElement = document.getElementById('modalDaysUsed');
                if (daysUsedElement) {
                    daysUsedElement.textContent = used + ' days';
                }

                // Notes
                const notesElement = document.getElementById('modalNotes');
                if (notesElement) {
                    notesElement.textContent = projectData.notes || 'No notes available';
                }

                // Full page link
                const fullPageElement = document.getElementById('modalViewFullPage');
                if (fullPageElement) {
                    fullPageElement.href = '/projects/' + projectData.id;
                }

                // Load files
                loadProjectFiles(projectData.id);

                // Show modal
                console.log('Showing modal...');
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                console.log('Modal should be visible now');

            } catch (error) {
                console.error('Error in openProjectModal:', error);
                alert('Error opening modal: ' + error.message);
            }
        }

        // Function to close project modal
        function closeProjectModal() {
            document.getElementById('projectModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentProjectData = null;
        }

        // Function to switch modal tabs
        function switchModalTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.project-modal-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // Update tab content
            document.querySelectorAll('.modal-tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tabName + 'Tab').classList.add('active');
        }

        // Function to toggle files view
        function toggleFilesView(view) {
            const container = document.getElementById('filesList');
            const buttons = document.querySelectorAll('.view-toggle-btn');

            // Update buttons
            buttons.forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.view === view) {
                    btn.classList.add('active');
                }
            });

            // Update container class
            container.className = 'files-container ' + view + '-view';
        }

        // Function to load project files
        function loadProjectFiles(projectId) {
            // Show loading state
            const container = document.getElementById('filesList');
            container.innerHTML = '<div class="no-files-message">Loading files...</div>';

            // Fetch files from server
            fetch('/api/projects/' + projectId + '/files')
                .then(response => response.json())
                .then(files => {
                    if (files.length === 0) {
                        container.innerHTML = '<div class="no-files-message">No files available for this project</div>';
                        return;
                    }

                    container.innerHTML = '';
                    files.forEach(file => {
                        const fileElement = createFileElement(file);
                        container.appendChild(fileElement);
                    });
                })
                .catch(error => {
                    console.error('Error loading files:', error);
                    container.innerHTML = '<div class="no-files-message">Error loading files</div>';
                });
        }

        // Function to create file element
        function createFileElement(file) {
            const div = document.createElement('div');
            div.className = 'file-item';
            div.onclick = () => openFileInLightbox(file);

            const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(file.filename);
            const isVideo = /\.(mp4|webm|ogg|mov)$/i.test(file.filename);
            const isPdf = /\.pdf$/i.test(file.filename);
            const isDoc = /\.(doc|docx|txt|rtf)$/i.test(file.filename);
            const isZip = /\.(zip|rar|7z|tar|gz)$/i.test(file.filename);

            let iconOrThumbnail = '';
            if (isImage && file.thumbnail_url) {
                iconOrThumbnail = `<img src="${file.thumbnail_url}" alt="${file.name}" class="file-thumbnail">`;
            } else if (isVideo && file.poster_url) {
                iconOrThumbnail = `<img src="${file.poster_url}" alt="${file.name}" class="file-thumbnail">`;
            } else {
                let iconText = 'FILE';
                if (isImage) iconText = 'IMG';
                else if (isVideo) iconText = 'VID';
                else if (isPdf) iconText = 'PDF';
                else if (isDoc) iconText = 'DOC';
                else if (isZip) iconText = 'ZIP';

                iconOrThumbnail = `<div class="file-icon">${iconText}</div>`;
            }

            div.innerHTML = `
                ${iconOrThumbnail}
                <div class="file-info">
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${formatFileSize(file.size)}</div>
                </div>
            `;

            return div;
        }

        // Function to open file in lightbox
        function openFileInLightbox(file) {
            const lightbox = document.getElementById('fileLightbox');
            const body = document.getElementById('lightboxBody');

            const isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(file.filename);
            const isVideo = /\.(mp4|webm|ogg|mov)$/i.test(file.filename);

            if (isImage) {
                body.innerHTML = `<img src="${file.url}" alt="${file.name}">`;
            } else if (isVideo) {
                body.innerHTML = `<video controls autoplay><source src="${file.url}" type="video/mp4"></video>`;
            } else {
                // For other files, offer download
                body.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #64748b;">
                        <div style="font-size: 48px; margin-bottom: 20px;">📄</div>
                        <h3>${file.name}</h3>
                        <p>File size: ${formatFileSize(file.size)}</p>
                        <a href="${file.url}" download class="download-all-btn" style="display: inline-flex; margin-top: 20px;">
                            Download File
                        </a>
                    </div>
                `;
            }

            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Keep modal body overflow hidden
        }

        // Function to close lightbox
        function closeLightbox() {
            document.getElementById('fileLightbox').style.display = 'none';
            document.body.style.overflow = 'hidden'; // Keep modal body overflow hidden
        }

        // Function to download all files
        function downloadAllFiles() {
            if (!currentProjectData) return;

            // Create download link for all files
            window.location.href = '/projects/' + currentProjectData.id + '/download-all';
        }

        // Helper functions
        function formatStatus(status) {
            const statusMap = {
                'new': 'New',
                'pending': 'Pending',
                'in_progress': 'In Progress',
                'completed': 'Completed',
                'finalized': 'Finalized',
                'cancelled': 'Cancelled'
            };
            return statusMap[status] || status;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Close modal when clicking outside (only add once)
        if (!window.modalEventListenersAdded) {
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('project-modal-overlay')) {
                closeProjectModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                if (document.getElementById('fileLightbox').style.display === 'flex') {
                    closeLightbox();
                } else if (document.getElementById('projectModal').style.display === 'flex') {
                    closeProjectModal();
                }
            }
        });
            window.modalEventListenersAdded = true;
        }
    </script>
@endsection
