@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold admin-text-primary">Assign Developer</h2>
            <p class="admin-text-secondary mt-2">Assign a developer to this project</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.projects') }}" class="admin-button admin-button-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Projects
            </a>
        </div>
    </div>
</div>

<!-- Project Information -->
<div class="admin-card mb-6">
    <div class="px-6 py-4 border-b admin-border">
        <h3 class="text-lg font-semibold admin-text-primary">Project Details</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium admin-text-primary mb-2">Project Name</h4>
                <p class="admin-text-secondary">{{ $project->name }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium admin-text-primary mb-2">Topic</h4>
                <p class="admin-text-secondary">{{ $project->topic ?? 'N/A' }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium admin-text-primary mb-2">Client</h4>
                <p class="admin-text-secondary">{{ $project->user->name }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium admin-text-primary mb-2">Status</h4>
                <span class="admin-badge
                    @if($project->status === 'completed') bg-gradient-to-r from-green-500 to-green-600
                    @elseif($project->status === 'in_progress') bg-gradient-to-r from-blue-500 to-blue-600
                    @elseif($project->status === 'pending') bg-gradient-to-r from-yellow-500 to-yellow-600
                    @else bg-gradient-to-r from-red-500 to-red-600 @endif">
                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Current Assignment -->
@if($project->assignedDeveloper)
<div class="admin-card mb-6">
    <div class="px-6 py-4 border-b admin-border">
        <h3 class="text-lg font-semibold admin-text-primary">Currently Assigned Developer</h3>
    </div>
    <div class="p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-12 w-12">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <span class="text-lg font-bold text-white">{{ substr($project->assignedDeveloper->name, 0, 1) }}</span>
                </div>
            </div>
            <div class="ml-4">
                <h4 class="text-lg font-semibold admin-text-primary">{{ $project->assignedDeveloper->name }}</h4>
                <p class="admin-text-secondary">{{ $project->assignedDeveloper->email }}</p>
                @if($project->assignedDeveloper->specialization)
                <p class="text-sm admin-text-secondary">{{ $project->assignedDeveloper->specialization->name }}</p>
                @endif
                @if($project->assigned_at)
                <p class="text-xs admin-text-secondary">Assigned: {{ $project->assigned_at->format('M d, Y H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Assign Developer Form -->
<div class="admin-card">
    <div class="px-6 py-4 border-b admin-border">
        <h3 class="text-lg font-semibold admin-text-primary">
            @if($project->assignedDeveloper)
                Change Developer Assignment
            @else
                Assign Developer
            @endif
        </h3>
        <p class="text-sm admin-text-secondary">Select a developer to assign to this project</p>
    </div>
    
    <form method="POST" action="{{ route('admin.projects.assign.store', $project->id) }}">
        @csrf
        @method('PUT')
        <div class="p-6">
            <div class="mb-6">
                <label class="block text-sm font-medium admin-text-primary mb-2">Select Developer</label>
                <select name="assigned_developer_id" required class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">Choose a developer...</option>
                    @foreach($developers as $developer)
                    <option value="{{ $developer->id }}" {{ old('assigned_developer_id') == $developer->id ? 'selected' : '' }}>
                        {{ $developer->name }} - {{ $developer->specialization->name ?? 'No specialization' }}
                        @if($developer->specialization)
                            ({{ $developer->specialization->name }})
                        @endif
                    </option>
                    @endforeach
                </select>
                @error('assigned_developer_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Developer List -->
            <div class="mb-6">
                <h4 class="text-sm font-medium admin-text-primary mb-3">Available Developers</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($developers as $developer)
                    <div class="border admin-border rounded-lg p-4 hover:admin-bg-secondary transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                    <span class="text-sm font-bold text-white">{{ substr($developer->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <h5 class="text-sm font-semibold admin-text-primary">{{ $developer->name }}</h5>
                                <p class="text-xs admin-text-secondary">{{ $developer->email }}</p>
                                @if($developer->specialization)
                                <p class="text-xs admin-text-secondary">{{ $developer->specialization->name }}</p>
                                @endif
                                @if($developer->skills && is_array($developer->skills))
                                <div class="mt-2">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($developer->skills, 0, 3) as $skill)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $skill }}
                                        </span>
                                        @endforeach
                                        @if(count($developer->skills) > 3)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                            +{{ count($developer->skills) - 3 }} more
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="px-6 py-4 border-t admin-border flex justify-end space-x-3">
            <a href="{{ route('admin.projects') }}" class="admin-button admin-button-secondary">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" class="admin-button">
                <i class="fas fa-user-plus mr-2"></i>
                @if($project->assignedDeveloper)
                    Change Assignment
                @else
                    Assign Developer
                @endif
            </button>
        </div>
    </form>
</div>
@endsection
