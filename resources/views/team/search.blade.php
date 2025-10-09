@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Find Developers</h1>
                <p class="text-lg text-gray-600">Discover skilled professionals for your projects</p>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('team.search') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by name, skills, or bio..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Specialization -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                        <select name="specialization" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                    
                    <!-- Experience Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Experience</label>
                        <select name="experience" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Levels</option>
                            <option value="junior" {{ request('experience') == 'junior' ? 'selected' : '' }}>Junior</option>
                            <option value="mid" {{ request('experience') == 'mid' ? 'selected' : '' }}>Mid-Level</option>
                            <option value="senior" {{ request('experience') == 'senior' ? 'selected' : '' }}>Senior</option>
                        </select>
                    </div>
                    
                    <!-- Search Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ $developers->total() }} Developer{{ $developers->total() !== 1 ? 's' : '' }} Found
                </h2>
                <div class="flex space-x-2">
                    <a href="{{ route('team.index') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Team
                    </a>
                </div>
            </div>
        </div>

        <!-- Developers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($developers as $developer)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-circle text-green-400 mr-1"></i>Available
                            </span>
                        </div>

                        <!-- Specialization -->
                        @if($developer->specialization)
                            <div class="mb-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-{{ $developer->specialization->icon }} text-blue-500 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-900">{{ $developer->specialization->name }}</span>
                                </div>
                                <p class="text-xs text-gray-500">{{ $developer->specialization->category_display }}</p>
                            </div>
                        @endif

                        <!-- Experience Level -->
                        <div class="mb-4">
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
                                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($developer->skills, 100) }}</p>
                            </div>
                        @endif

                        <!-- Bio -->
                        @if($developer->bio)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 line-clamp-2">{{ Str::limit($developer->bio, 120) }}</p>
                            </div>
                        @endif

                        <!-- Links -->
                        <div class="flex space-x-3 mb-4">
                            @if($developer->portfolio_url)
                                <a href="{{ $developer->portfolio_url }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                    <i class="fas fa-globe"></i>
                                </a>
                            @endif
                            @if($developer->linkedin_url)
                                <a href="{{ $developer->linkedin_url }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            @endif
                            @if($developer->github_url)
                                <a href="{{ $developer->github_url }}" target="_blank" class="text-gray-600 hover:text-gray-500">
                                    <i class="fab fa-github"></i>
                                </a>
                            @endif
                        </div>

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

                        <!-- Contact Button -->
                        <div class="flex space-x-2">
                            <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-envelope mr-1"></i>Contact
                            </button>
                            <button class="flex-1 bg-gray-600 hover:bg-gray-700 text-white text-center px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i>View Profile
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No developers found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your search criteria or browse all specializations.</p>
                    <a href="{{ route('team.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Browse All
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
</div>
@endsection
