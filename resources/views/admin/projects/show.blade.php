@extends('layouts.admin')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

<!-- Page Header -->
<div class="mb-10">
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-6">
            <a href="{{ route('admin.projects') }}" class="admin-text-secondary hover:admin-text-primary transition-colors duration-200">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
            <div>
                <h2 class="text-4xl font-bold admin-text-primary mb-2">{{ $project->name }}</h2>
                <p class="admin-text-secondary text-lg">Project Details & Management</p>
            </div>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.projects.edit', $project) }}" class="admin-button px-6 py-3">
                <i class="fas fa-edit mr-2"></i>Edit Project
            </a>
            @if(!$project->assignedDeveloper)
                <a href="{{ route('admin.projects.assign', $project) }}" class="admin-button admin-button-success px-6 py-3">
                    <i class="fas fa-user-plus mr-2"></i>Assign Developer
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Project Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Hours Worked</p>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total_hours_worked'] ?? 0 }}</p>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Progress</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['progress_percentage'] ?? 0 }}%</p>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-line text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Documents</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['documents_count'] ?? 0 }}</p>
            </div>
            <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-alt text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Updates</p>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['updates_count'] ?? 0 }}</p>
            </div>
            <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-sync text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Project Information -->
        <div class="admin-card p-8">
            <h2 class="text-2xl font-semibold admin-text-primary mb-8">Project Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Project Name</label>
                    <p class="admin-text-primary text-lg">{{ $project->name }}</p>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Topic</label>
                    <p class="admin-text-primary text-lg">{{ $project->topic ?? 'Not specified' }}</p>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Status</label>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($project->status === 'in_progress') bg-blue-100 text-blue-800
                        @elseif($project->status === 'completed') bg-green-100 text-green-800
                        @elseif($project->status === 'on_hold') bg-yellow-100 text-yellow-800
                        @elseif($project->status === 'cancelled') bg-red-100 text-red-800
                        @elseif($project->status === 'finalized') bg-purple-100 text-purple-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Priority</label>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($project->priority === 'high') bg-red-100 text-red-800
                        @elseif($project->priority === 'medium') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ ucfirst($project->priority ?? 'low') }}
                    </span>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Progress</label>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-3 mr-3">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-300" style="width: {{ $stats['progress_percentage'] ?? 0 }}%"></div>
                        </div>
                        <span class="text-sm font-semibold admin-text-primary">{{ $stats['progress_percentage'] ?? 0 }}%</span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Total Days</label>
                    <p class="admin-text-primary text-lg">{{ $project->total_days ?? 'Not specified' }}</p>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Estimated Hours</label>
                    <p class="admin-text-primary text-lg">{{ $stats['estimated_hours'] ?? 'Not specified' }}</p>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Hours Worked</label>
                    <p class="admin-text-primary text-lg">{{ $stats['total_hours_worked'] ?? 0 }} hours</p>
                </div>
            </div>
        </div>

        <!-- Team Information -->
        <div class="admin-card p-8">
            <h2 class="text-2xl font-semibold admin-text-primary mb-8">Team Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Client Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold admin-text-primary mb-4">Client</h3>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-blue-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="font-semibold admin-text-primary">{{ $project->user->name }}</p>
                            <p class="text-sm admin-text-secondary">{{ $project->user->email }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Developer Info -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold admin-text-primary mb-4">Assigned Developer</h3>
                    @if($project->assignedDeveloper)
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-code text-green-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="font-semibold admin-text-primary">{{ $project->assignedDeveloper->name }}</p>
                                <p class="text-sm admin-text-secondary">{{ $project->assignedDeveloper->email }}</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-user-plus admin-text-secondary text-4xl mb-4"></i>
                            <p class="admin-text-secondary">No developer assigned</p>
                            <a href="{{ route('admin.projects.assign', $project) }}" class="admin-button admin-button-success mt-4">
                                <i class="fas fa-user-plus mr-2"></i>Assign Developer
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="admin-card p-8">
            <h2 class="text-2xl font-semibold admin-text-primary mb-8">Timeline</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Start Date</label>
                    <p class="admin-text-primary text-lg">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}</p>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">End Date</label>
                    <p class="admin-text-primary text-lg">{{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}</p>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Delivery Date</label>
                    <p class="admin-text-primary text-lg">{{ $project->delivery_date ? $project->delivery_date->format('M d, Y') : 'Not set' }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Updates -->
        @if($project->progressUpdates->count() > 0)
        <div class="admin-card p-8">
            <h2 class="text-2xl font-semibold admin-text-primary mb-8">Recent Updates</h2>
            
            <div class="space-y-6">
                @foreach($project->progressUpdates->take(5) as $update)
                <div class="border-l-4 border-blue-500 pl-6 py-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold admin-text-primary">
                            <i class="fas fa-sync text-blue-500 mr-2"></i>
                            {{ $update->title ?? 'Progress Update' }}
                        </h4>
                        <span class="text-sm admin-text-secondary">{{ $update->created_at->format('M d, Y') }}</span>
                    </div>
                    <p class="admin-text-primary mb-2">{{ $update->description ?? 'No description provided' }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm admin-text-secondary">
                            <i class="fas fa-user mr-2"></i>
                            <span>Updated by: {{ $update->user->name ?? 'Unknown' }}</span>
                            @if($update->progress_percentage)
                            <span class="ml-4">
                                <i class="fas fa-chart-line mr-1"></i>
                                Progress: {{ $update->progress_percentage }}%
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($update->files && $update->files->count() > 0)
                                @foreach($update->files->take(2) as $file)
                                <a href="{{ route('filament.admin.projects.documents.download', ['project' => $project, 'document' => $file]) }}" class="admin-button admin-button-sm">
                                    <i class="fas fa-download mr-1"></i>{{ $file->original_name }}
                                </a>
                                @endforeach
                            @else
                                <!-- Look for project documents that match this update's description -->
                                @php
                                    $matchingDocuments = $project->documents->filter(function($doc) use ($update) {
                                        return stripos($doc->name, $update->description) !== false || 
                                               stripos($update->description, $doc->name) !== false;
                                    });
                                @endphp
                                @if($matchingDocuments->count() > 0)
                                    @foreach($matchingDocuments->take(2) as $doc)
                                    <a href="{{ route('filament.admin.projects.documents.download', ['project' => $project, 'document' => $doc]) }}" class="admin-button admin-button-sm">
                                        <i class="fas fa-download mr-1"></i>{{ $doc->name }}
                                    </a>
                                    @endforeach
                                @else
                                    <!-- Create a download link for the update description as a text file -->
                                    <a href="data:text/plain;charset=utf-8,{{ urlencode($update->description ?? 'Progress Update') }}" download="{{ $update->description ?? 'progress-update' }}.txt" class="admin-button admin-button-sm">
                                        <i class="fas fa-download mr-1"></i>{{ $update->description ?? 'Download' }}
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Time Logs -->
        @if($project->timeLogs->count() > 0)
        <div class="admin-card p-8">
            <h2 class="text-2xl font-semibold admin-text-primary mb-8">Time Logs</h2>
            
            <div class="space-y-4">
                @foreach($project->timeLogs->take(10) as $log)
                <div class="flex items-center justify-between p-4 border admin-border rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-green-600 mr-3"></i>
                        <div>
                            <p class="font-semibold admin-text-primary">{{ $log->description ?? 'Time logged' }}</p>
                            <p class="text-sm admin-text-secondary">By: {{ $log->user->name ?? 'Unknown' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold admin-text-primary">{{ $log->hours_spent }} hours</p>
                        <p class="text-sm admin-text-secondary">{{ $log->work_date ? $log->work_date->format('M d, Y') : $log->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Project Description -->
        @if($project->description || $project->notes || $project->client_requirements)
        <div class="admin-card p-8">
            <h2 class="text-2xl font-semibold admin-text-primary mb-8">Project Details</h2>
            
            <div class="space-y-8">
                @if($project->description)
                <div>
                    <h3 class="text-lg font-semibold admin-text-primary mb-4">Description</h3>
                    <p class="admin-text-primary leading-relaxed">{{ $project->description }}</p>
                </div>
                @endif
                
                @if($project->notes)
                <div>
                    <h3 class="text-lg font-semibold admin-text-primary mb-4">Notes</h3>
                    <p class="admin-text-primary leading-relaxed">{{ $project->notes }}</p>
                </div>
                @endif
                
                @if($project->client_requirements)
                <div>
                    <h3 class="text-lg font-semibold admin-text-primary mb-4">Client Requirements</h3>
                    <div class="admin-text-primary leading-relaxed whitespace-pre-line">{{ $project->client_requirements }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-8">
        <!-- Quick Actions -->
        <div class="admin-card p-6">
            <h3 class="text-xl font-semibold admin-text-primary mb-6">Quick Actions</h3>
            
            <div class="space-y-4">
                <a href="{{ route('admin.projects.edit', $project) }}" class="w-full admin-button flex items-center justify-center py-3">
                    <i class="fas fa-edit mr-3"></i>Edit Project
                </a>
                
                @if(!$project->assignedDeveloper)
                    <a href="{{ route('admin.projects.assign', $project) }}" class="w-full admin-button admin-button-success flex items-center justify-center py-3">
                        <i class="fas fa-user-plus mr-3"></i>Assign Developer
                    </a>
                @endif
                
                <a href="{{ route('admin.projects') }}" class="w-full admin-button admin-button-secondary flex items-center justify-center py-3">
                    <i class="fas fa-arrow-left mr-3"></i>Back to Projects
                </a>
            </div>
        </div>

        <!-- Project Statistics -->
        <div class="admin-card p-6">
            <h3 class="text-xl font-semibold admin-text-primary mb-6">Project Statistics</h3>
            
            <div class="space-y-6">
                <div class="flex justify-between items-center py-3 border-b admin-border">
                    <span class="text-sm font-semibold admin-text-primary">Total Days</span>
                    <span class="text-2xl font-bold admin-text-primary">{{ $project->total_days ?? 'N/A' }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b admin-border">
                    <span class="text-sm font-semibold admin-text-primary">Days Used</span>
                    <span class="text-2xl font-bold admin-text-primary">{{ $stats['total_days_used'] ?? 0 }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b admin-border">
                    <span class="text-sm font-semibold admin-text-primary">Hours Worked</span>
                    <span class="text-2xl font-bold admin-text-primary">{{ $stats['total_hours_worked'] ?? 0 }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b admin-border">
                    <span class="text-sm font-semibold admin-text-primary">Documents</span>
                    <span class="text-2xl font-bold admin-text-primary">{{ $stats['documents_count'] ?? 0 }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm font-semibold admin-text-primary">Created</span>
                    <span class="text-lg admin-text-secondary">{{ $project->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="admin-card p-6">
            <h3 class="text-xl font-semibold admin-text-primary mb-6">Contact Information</h3>
            
            <div class="space-y-6">
                <div class="flex items-center py-2">
                    <i class="fas fa-user admin-text-secondary mr-4 text-lg"></i>
                    <div>
                        <p class="admin-text-primary text-lg">{{ $project->user->name }}</p>
                        <p class="admin-text-secondary">{{ $project->user->email }}</p>
                    </div>
                </div>
                
                @if($project->assignedDeveloper)
                    <div class="flex items-center py-2">
                        <i class="fas fa-code admin-text-secondary mr-4 text-lg"></i>
                        <div>
                            <p class="admin-text-primary text-lg">{{ $project->assignedDeveloper->name }}</p>
                            <p class="admin-text-secondary">{{ $project->assignedDeveloper->email }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection