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
            <a href="{{ route('admin.developers.index') }}" class="admin-text-secondary hover:admin-text-primary transition-colors duration-200">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
            <div>
                <h2 class="text-4xl font-bold admin-text-primary mb-2">{{ $developer->name }}</h2>
                <p class="admin-text-secondary text-lg">Developer Profile & Performance</p>
            </div>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.developers.edit', $developer) }}" class="admin-button px-6 py-3">
                <i class="fas fa-edit mr-2"></i>Edit Developer
            </a>
            <form action="{{ route('admin.developers.toggle-availability', $developer) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="{{ $developer->is_available ? 'admin-button-warning' : 'admin-button-success' }} px-6 py-3">
                    <i class="fas fa-{{ $developer->is_available ? 'pause' : 'play' }} mr-2"></i>
                    {{ $developer->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <!-- Developer Info -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Basic Information -->
        <div class="admin-card p-8">
            <h2 class="text-2xl font-semibold admin-text-primary mb-8">Developer Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Full Name</label>
                    <p class="admin-text-primary text-lg">{{ $developer->name }}</p>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Email</label>
                    <p class="admin-text-primary text-lg">{{ $developer->email }}</p>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Phone</label>
                    <p class="admin-text-primary text-lg">{{ $developer->phone ?? 'Not provided' }}</p>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Experience Level</label>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($developer->experience_level === 'junior') bg-yellow-100 text-yellow-800
                        @elseif($developer->experience_level === 'mid') bg-blue-100 text-blue-800
                        @else bg-purple-100 text-purple-800 @endif">
                        {{ ucfirst($developer->experience_level) }}
                    </span>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Specialization</label>
                    @if($developer->specialization)
                        <div class="flex items-center">
                            <i class="fas fa-{{ $developer->specialization->icon }} text-blue-500 mr-3 text-lg"></i>
                            <span class="admin-text-primary text-lg">{{ $developer->specialization->name }}</span>
                        </div>
                        <p class="text-sm admin-text-secondary mt-2">{{ $developer->specialization->category_display }}</p>
                    @else
                        <p class="admin-text-secondary text-lg">No specialization assigned</p>
                    @endif
                </div>
                
                <div class="space-y-3">
                    <label class="block text-sm font-semibold admin-text-primary mb-3">Status</label>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        {{ $developer->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas fa-{{ $developer->is_available ? 'check-circle' : 'times-circle' }} mr-2"></i>
                        {{ $developer->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                </div>
            </div>
                
            @if($developer->skills && is_array($developer->skills) && count($developer->skills) > 0)
                <div class="mt-10 pt-8 border-t admin-border">
                    <label class="block text-lg font-semibold admin-text-primary mb-6">Skills & Expertise</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($developer->skills as $skill)
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @elseif($developer->skills && is_string($developer->skills))
                <div class="mt-10 pt-8 border-t admin-border">
                    <label class="block text-lg font-semibold admin-text-primary mb-4">Skills & Expertise</label>
                    <p class="admin-text-primary text-lg">{{ $developer->skills }}</p>
                </div>
            @endif
            
            @if($developer->bio)
                <div class="mt-10 pt-8 border-t admin-border">
                    <label class="block text-lg font-semibold admin-text-primary mb-4">Bio</label>
                    <p class="admin-text-primary text-lg leading-relaxed">{{ $developer->bio }}</p>
                </div>
            @endif
        </div>

        <!-- Assigned Projects -->
        <div class="admin-card p-8">
            <h2 class="text-2xl font-semibold admin-text-primary mb-8">Assigned Projects</h2>
            
            @if($developer->assignedProjects->count() > 0)
                <div class="space-y-6">
                    @foreach($developer->assignedProjects as $project)
                        <div class="border admin-border rounded-xl p-6 hover:shadow-lg transition-all duration-200">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold admin-text-primary text-lg mb-2">{{ $project->name }}</h3>
                                    <p class="text-sm admin-text-secondary leading-relaxed">{{ Str::limit($project->notes, 120) }}</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ml-4
                                    @if($project->status === 'completed') bg-green-100 text-green-800
                                    @elseif($project->status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($project->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm admin-text-secondary pt-4 border-t admin-border">
                                <div class="flex items-center space-x-6">
                                    <span class="flex items-center"><i class="fas fa-calendar mr-2"></i>{{ $project->created_at->format('M d, Y') }}</span>
                                    @if($project->deadline)
                                        <span class="flex items-center"><i class="fas fa-clock mr-2"></i>Due: {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center"><i class="fas fa-user mr-2"></i>{{ $project->user->name }}</span>
                                    @if($project->assignedAdministrator)
                                        <span class="flex items-center"><i class="fas fa-user-tie mr-2"></i>{{ $project->assignedAdministrator->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-folder-open admin-text-secondary text-6xl mb-6"></i>
                    <p class="admin-text-secondary text-lg">No projects assigned to this developer</p>
                </div>
            @endif
        </div>
        </div>

    <!-- Stats & Actions -->
    <div class="space-y-8">
        <!-- Performance Stats -->
        <div class="admin-card p-6">
            <h3 class="text-xl font-semibold admin-text-primary mb-6">Performance Stats</h3>
            
            <div class="space-y-6">
                <div class="flex justify-between items-center py-3 border-b admin-border">
                    <span class="text-sm font-semibold admin-text-primary">Total Projects</span>
                    <span class="text-2xl font-bold admin-text-primary">{{ $stats['total_projects'] }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b admin-border">
                    <span class="text-sm font-semibold admin-text-primary">In Progress</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $stats['in_progress'] }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3 border-b admin-border">
                    <span class="text-sm font-semibold admin-text-primary">Completed</span>
                    <span class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</span>
                </div>
                
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm font-semibold admin-text-primary">Hours This Month</span>
                    <span class="text-2xl font-bold text-purple-600">{{ number_format($stats['hours_logged_this_month'], 1) }}h</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="admin-card p-6">
            <h3 class="text-xl font-semibold admin-text-primary mb-6">Quick Actions</h3>
            
            <div class="space-y-4">
                <a href="{{ route('admin.developers.edit', $developer) }}" class="w-full admin-button flex items-center justify-center py-3">
                    <i class="fas fa-edit mr-3"></i>Edit Developer
                </a>
                
                <form action="{{ route('admin.developers.toggle-availability', $developer) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full {{ $developer->is_available ? 'admin-button-warning' : 'admin-button-success' }} flex items-center justify-center py-3">
                        <i class="fas fa-{{ $developer->is_available ? 'pause' : 'play' }} mr-3"></i>
                        {{ $developer->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                    </button>
                </form>
                
                <a href="mailto:{{ $developer->email }}" class="w-full admin-button admin-button-secondary flex items-center justify-center py-3">
                    <i class="fas fa-envelope mr-3"></i>Send Email
                </a>
            </div>
        </div>

        <!-- Contact Information -->
        @if($developer->phone || $developer->address)
            <div class="admin-card p-6">
                <h3 class="text-xl font-semibold admin-text-primary mb-6">Contact Information</h3>
                
                <div class="space-y-6">
                    @if($developer->phone)
                        <div class="flex items-center py-2">
                            <i class="fas fa-phone admin-text-secondary mr-4 text-lg"></i>
                            <span class="admin-text-primary text-lg">{{ $developer->phone }}</span>
                        </div>
                    @endif
                    
                    @if($developer->address)
                        <div class="flex items-start py-2">
                            <i class="fas fa-map-marker-alt admin-text-secondary mr-4 mt-1 text-lg"></i>
                            <div>
                                <p class="admin-text-primary text-lg">{{ $developer->address }}</p>
                                @if($developer->city)
                                    <p class="admin-text-secondary">{{ $developer->city }}, {{ $developer->country }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
