@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold admin-text-primary">Developer Management</h2>
            <p class="admin-text-secondary mt-2">Manage developers and their specializations</p>
        </div>
        <div class="flex space-x-3">
            <button class="admin-button">
                <i class="fas fa-plus mr-2"></i>Add Developer
            </button>
            <button class="admin-button admin-button-secondary">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>
</div>

<!-- Developer Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-code text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Total Developers</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\User::where('role', 'developer')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-check text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Available</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\User::where('role', 'developer')->where('is_available', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-folder text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Active Projects</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\Project::where('status', 'in_progress')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Hours Logged</p>
                <p class="text-2xl font-bold admin-text-primary">{{ rand(500, 2000) }}h</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="admin-card mb-6">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Search</label>
                <input type="text" placeholder="Search developers..." class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
            </div>
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Specialization</label>
                <select class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">All Specializations</option>
                    <option value="frontend">Frontend Development</option>
                    <option value="backend">Backend Development</option>
                    <option value="fullstack">Full Stack Development</option>
                    <option value="mobile">Mobile Development</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Experience</label>
                <select class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">All Levels</option>
                    <option value="junior">Junior</option>
                    <option value="mid">Mid-level</option>
                    <option value="senior">Senior</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="admin-button w-full">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Developers Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach(\App\Models\User::where('role', 'developer')->latest()->take(9)->get() as $developer)
    <div class="admin-card hover:shadow-lg transition-all duration-300">
        <div class="p-6">
            <!-- Developer Header -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <span class="text-lg font-bold text-white">{{ substr($developer->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold admin-text-primary">{{ $developer->name }}</h3>
                        <p class="text-sm admin-text-secondary">{{ $developer->email }}</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 transition-colors">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="text-yellow-600 hover:text-yellow-900 transition-colors">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
            
            <!-- Status and Experience -->
            <div class="flex items-center justify-between mb-4">
                <span class="admin-badge
                    @if($developer->is_available ?? true) bg-gradient-to-r from-green-500 to-green-600
                    @else bg-gradient-to-r from-red-500 to-red-600 @endif">
                    {{ ($developer->is_available ?? true) ? 'Available' : 'Busy' }}
                </span>
                <span class="admin-badge bg-gradient-to-r from-purple-500 to-purple-600">
                    {{ ucfirst($developer->experience_level ?? 'Mid-level') }}
                </span>
            </div>
            
            <!-- Specialization -->
            @if($developer->specialization)
            <div class="mb-4">
                <div class="flex items-center">
                    <i class="fas fa-tag text-blue-500 mr-2"></i>
                    <span class="text-sm font-medium admin-text-primary">{{ $developer->specialization->name }}</span>
                </div>
            </div>
            @endif
            
            <!-- Skills -->
            @if($developer->skills && is_array($developer->skills) && count($developer->skills) > 0)
            <div class="mb-4">
                <div class="flex flex-wrap gap-1">
                    @foreach(array_slice($developer->skills, 0, 3) as $skill)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $skill }}
                    </span>
                    @endforeach
                    @if(count($developer->skills) > 3)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                        +{{ count($developer->skills) - 3 }}
                    </span>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-center">
                    <div class="text-lg font-bold admin-text-primary">{{ $developer->assignedProjects->count() }}</div>
                    <div class="text-xs admin-text-secondary">Projects</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold admin-text-primary">{{ $developer->assignedProjects->where('status', 'completed')->count() }}</div>
                    <div class="text-xs admin-text-secondary">Completed</div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex space-x-2">
                <button class="flex-1 admin-button admin-button-secondary text-sm">
                    <i class="fas fa-user-plus mr-1"></i>Assign
                </button>
                <button class="flex-1 admin-button text-sm">
                    <i class="fas fa-envelope mr-1"></i>Contact
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Load More -->
<div class="text-center mt-8">
    <button class="admin-button admin-button-secondary">
        <i class="fas fa-plus mr-2"></i>Load More Developers
    </button>
</div>
@endsection
