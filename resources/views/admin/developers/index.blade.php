@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Developer Management</h1>
            <p class="text-gray-600 mt-2">Manage all developers and IT professionals in your platform</p>
        </div>
        <a href="{{ route('admin.developers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Add New Developer
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                <select name="specialization" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Experience Level</label>
                <select name="experience" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Levels</option>
                    <option value="junior" {{ request('experience') == 'junior' ? 'selected' : '' }}>Junior</option>
                    <option value="mid" {{ request('experience') == 'mid' ? 'selected' : '' }}>Mid-Level</option>
                    <option value="senior" {{ request('experience') == 'senior' ? 'selected' : '' }}>Senior</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                <select name="availability" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All</option>
                    <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Developers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($developers as $developer)
            <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-gray-900">{{ $developer->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $developer->email }}</p>
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
                                <i class="fas fa-tag text-gray-400 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Specialization</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-{{ $developer->specialization->icon }} text-blue-500 mr-2"></i>
                                <span class="text-sm text-gray-900">{{ $developer->specialization->name }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $developer->specialization->category_display }}</p>
                        </div>
                    @endif

                    <!-- Experience Level -->
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-chart-line text-gray-400 mr-2"></i>
                            <span class="text-sm font-medium text-gray-700">Experience</span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($developer->experience_level === 'junior') bg-yellow-100 text-yellow-800
                            @elseif($developer->experience_level === 'mid') bg-blue-100 text-blue-800
                            @else bg-purple-100 text-purple-800 @endif">
                            {{ ucfirst($developer->experience_level) }}
                        </span>
                    </div>

                    <!-- Skills -->
                    @if($developer->skills)
                        <div class="mb-4">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-tools text-gray-400 mr-2"></i>
                                <span class="text-sm font-medium text-gray-700">Skills</span>
                            </div>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($developer->skills, 100) }}</p>
                        </div>
                    @endif

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">{{ $developer->assignedProjects->count() }}</div>
                            <div class="text-xs text-gray-500">Projects</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">{{ $developer->assignedProjects->where('status', 'completed')->count() }}</div>
                            <div class="text-xs text-gray-500">Completed</div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.developers.show', $developer) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                        <a href="{{ route('admin.developers.edit', $developer) }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <form method="POST" action="{{ route('admin.developers.toggle-availability', $developer) }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full {{ $developer->is_available ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-{{ $developer->is_available ? 'pause' : 'play' }} mr-1"></i>
                                {{ $developer->is_available ? 'Pause' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No developers found</h3>
                <p class="text-gray-600 mb-6">Get started by adding your first developer to the platform.</p>
                <a href="{{ route('admin.developers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
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
</div>
@endsection

