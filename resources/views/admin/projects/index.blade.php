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
        <div>
            <h2 class="text-4xl font-bold admin-text-primary mb-2">Project Management</h2>
            <p class="admin-text-secondary text-lg">Manage and monitor all projects with detailed insights</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.projects.create') }}" class="admin-button px-6 py-3">
                <i class="fas fa-plus mr-2"></i>Add Project
            </a>
            <button class="admin-button admin-button-secondary px-6 py-3">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Total Projects</p>
                <p class="text-3xl font-bold admin-text-primary">{{ $stats['total_projects'] ?? 0 }}</p>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-project-diagram text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Active Projects</p>
                <p class="text-3xl font-bold text-green-600">{{ $stats['active_projects'] ?? 0 }}</p>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-tasks text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Completed</p>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['completed'] ?? 0 }}</p>
            </div>
            <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">On Hold</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['on_hold'] ?? 0 }}</p>
            </div>
            <div class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-pause text-yellow-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="admin-card p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold admin-text-secondary mb-2">Finalized</p>
                <p class="text-3xl font-bold text-indigo-600">{{ $stats['finalized'] ?? 0 }}</p>
            </div>
            <div class="w-16 h-16 bg-indigo-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-flag-checkered text-indigo-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="admin-card mb-8">
    <form method="GET" class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..." class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
            </div>
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="on_hold" {{ request('status') === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Client</label>
                <select name="client" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">All Clients</option>
                    @foreach(\App\Models\User::where('role', 'client')->get() as $client)
                        <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full admin-button admin-button-secondary">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Projects Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-8">
    @forelse($projects as $project)
        <div class="admin-card hover:shadow-lg transition-all duration-200">
            <div class="p-6">
                <!-- Project Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-xl mr-4">
                            {{ substr($project->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold admin-text-primary text-lg mb-1">{{ $project->name }}</h3>
                            <p class="admin-text-secondary text-sm">{{ Str::limit($project->notes ?? $project->description, 60) }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        @if($project->status === 'in_progress') bg-blue-100 text-blue-800
                        @elseif($project->status === 'completed') bg-green-100 text-green-800
                        @elseif($project->status === 'on_hold') bg-yellow-100 text-yellow-800
                        @elseif($project->status === 'cancelled') bg-red-100 text-red-800
                        @elseif($project->status === 'finalized') bg-purple-100 text-purple-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>

                <!-- Project Details -->
                <div class="space-y-4 mb-6">
                    <!-- Client Info -->
                    <div class="flex items-center">
                        <i class="fas fa-user admin-text-secondary mr-3"></i>
                        <div>
                            <p class="text-sm font-semibold admin-text-primary">{{ $project->user->name }}</p>
                            <p class="text-xs admin-text-secondary">{{ $project->user->email }}</p>
                        </div>
                    </div>

                    <!-- Developer Info -->
                    <div class="flex items-center">
                        <i class="fas fa-code admin-text-secondary mr-3"></i>
                        <div>
                            @if($project->assignedDeveloper)
                                <p class="text-sm font-semibold admin-text-primary">{{ $project->assignedDeveloper->name }}</p>
                                <p class="text-xs admin-text-secondary">{{ $project->assignedDeveloper->email }}</p>
                            @else
                                <p class="text-sm admin-text-secondary italic">Not assigned</p>
                            @endif
                        </div>
                    </div>

                    <!-- Progress -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold admin-text-primary">Progress</span>
                            <span class="text-sm admin-text-secondary">{{ $project->progress ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-300" style="width: {{ $project->progress ?? 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-calendar admin-text-secondary mr-2"></i>
                            <span class="admin-text-secondary">Created: {{ $project->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($project->deadline)
                            <div class="flex items-center">
                                <i class="fas fa-clock admin-text-secondary mr-2"></i>
                                <span class="admin-text-secondary">Due: {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex space-x-2">
                    <a href="{{ route('admin.projects.show', $project) }}" class="flex-1 admin-button admin-button-sm">
                        <i class="fas fa-eye mr-2"></i>View
                    </a>
                    <a href="{{ route('admin.projects.edit', $project) }}" class="flex-1 admin-button admin-button-secondary admin-button-sm">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    @if(!$project->assignedDeveloper)
                        <a href="{{ route('admin.projects.assign', $project) }}" class="flex-1 admin-button admin-button-success admin-button-sm">
                            <i class="fas fa-user-plus mr-2"></i>Assign
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-16">
            <i class="fas fa-folder-open admin-text-secondary text-8xl mb-6"></i>
            <h3 class="text-2xl font-bold admin-text-primary mb-4">No projects found</h3>
            <p class="admin-text-secondary text-lg mb-8">Get started by creating your first project</p>
            <a href="{{ route('admin.projects.create') }}" class="admin-button px-8 py-4">
                <i class="fas fa-plus mr-2"></i>Create First Project
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($projects->hasPages())
    <div class="mt-10">
        {{ $projects->links() }}
    </div>
@endif
</div>
@endsection