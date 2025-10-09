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
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold admin-text-primary">Developer Management</h2>
            <p class="admin-text-secondary mt-2">Manage all developers and IT professionals in your platform</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.developers.create') }}" class="admin-button">
                <i class="fas fa-plus mr-2"></i>Add Developer
            </a>
            <button class="admin-button admin-button-secondary">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="admin-card mb-6">
    <form method="GET" class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Specialization</label>
                <select name="specialization" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">All Specializations</option>
                    @foreach($specializations->groupBy('category') as $category => $specs)
                        <optgroup label="{{ ucfirst(str_replace('_', ' ', $category)) }}">
                            @foreach($specs as $spec)
                                <option value="{{ $spec->id }}" {{ request('specialization') == $spec->id ? 'selected' : '' }}>
                                    {{ $spec->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Experience Level</label>
                <select name="experience" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">All Levels</option>
                    <option value="junior" {{ request('experience') == 'junior' ? 'selected' : '' }}>Junior</option>
                    <option value="mid" {{ request('experience') == 'mid' ? 'selected' : '' }}>Mid-Level</option>
                    <option value="senior" {{ request('experience') == 'senior' ? 'selected' : '' }}>Senior</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Availability</label>
                <select name="availability" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">All</option>
                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
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

<!-- Developers Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($developers as $developer)
        <div class="admin-card hover:shadow-lg transition-all duration-200">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold admin-text-primary">{{ $developer->name }}</h3>
                            <p class="text-sm admin-text-secondary">{{ $developer->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($developer->is_available)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-circle text-green-400 mr-1"></i>Available
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-circle text-red-400 mr-1"></i>Unavailable
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Specialization -->
                @if($developer->specialization)
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-tag admin-text-secondary mr-2"></i>
                            <span class="text-sm font-medium admin-text-primary">Specialization</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-{{ $developer->specialization->icon }} text-blue-500 mr-2"></i>
                            <span class="text-sm admin-text-primary">{{ $developer->specialization->name }}</span>
                        </div>
                        <p class="text-xs admin-text-secondary mt-1">{{ $developer->specialization->category_display }}</p>
                    </div>
                @endif

                <!-- Experience Level -->
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-chart-line admin-text-secondary mr-2"></i>
                        <span class="text-sm font-medium admin-text-primary">Experience</span>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($developer->experience_level === 'junior') bg-yellow-100 text-yellow-800
                        @elseif($developer->experience_level === 'mid') bg-blue-100 text-blue-800
                        @else bg-purple-100 text-purple-800 @endif">
                        {{ ucfirst($developer->experience_level) }}
                    </span>
                </div>

                <!-- Skills -->
                @if($developer->skills && is_array($developer->skills) && count($developer->skills) > 0)
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-tools admin-text-secondary mr-2"></i>
                            <span class="text-sm font-medium admin-text-primary">Skills</span>
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($developer->skills, 0, 5) as $skill)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $skill }}
                                </span>
                            @endforeach
                            @if(count($developer->skills) > 5)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    +{{ count($developer->skills) - 5 }} more
                                </span>
                            @endif
                        </div>
                    </div>
                @elseif($developer->skills && is_string($developer->skills))
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-tools admin-text-secondary mr-2"></i>
                            <span class="text-sm font-medium admin-text-primary">Skills</span>
                        </div>
                        <p class="text-sm admin-text-secondary line-clamp-2">{{ Str::limit($developer->skills, 100) }}</p>
                    </div>
                @endif

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-lg font-semibold admin-text-primary">{{ $developer->assignedProjects->count() }}</div>
                        <div class="text-xs admin-text-secondary">Projects</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-semibold admin-text-primary">{{ $developer->assignedProjects->where('status', 'completed')->count() }}</div>
                        <div class="text-xs admin-text-secondary">Completed</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <a href="{{ route('admin.developers.show', $developer) }}" class="flex-1 admin-button admin-button-sm">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                    <a href="{{ route('admin.developers.edit', $developer) }}" class="flex-1 admin-button admin-button-secondary admin-button-sm">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form method="POST" action="{{ route('admin.developers.toggle-availability', $developer) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full {{ $developer->is_available ? 'admin-button-warning' : 'admin-button-success' }} admin-button-sm">
                            <i class="fas fa-{{ $developer->is_available ? 'pause' : 'play' }} mr-1"></i>
                            {{ $developer->is_available ? 'Pause' : 'Activate' }}
                        </button>
                    </form>
                </div>
                </div>
            </div>
    @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-users admin-text-secondary text-6xl mb-4"></i>
            <h3 class="text-lg font-medium admin-text-primary mb-2">No developers found</h3>
            <p class="admin-text-secondary mb-6">Get started by adding your first developer to the platform.</p>
            <a href="{{ route('admin.developers.create') }}" class="admin-button">
                <i class="fas fa-plus mr-2"></i>Add First Developer
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($developers->hasPages())
    <div class="mt-8">
        {{ $developers->links() }}
    </div>
@endif
@endsection

