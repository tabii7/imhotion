@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-4">
                        <a href="{{ route('team.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-{{ $specialization->icon }} text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">{{ $specialization->name }}</h1>
                                <p class="text-lg text-gray-600">{{ $specialization->category_display }}</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 max-w-3xl">{{ $specialization->description }}</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-blue-600">{{ $developers->total() }}</div>
                    <div class="text-sm text-gray-500">Available Developers</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Specialization Details -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Specialization Details</h3>
                    
                    <!-- Skills -->
                    @if($specialization->skills && count($specialization->skills) > 0)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Key Skills</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($specialization->skills as $skill)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Tools -->
                    @if($specialization->tools && count($specialization->tools) > 0)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Common Tools</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($specialization->tools as $tool)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $tool }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="space-y-3">
                        <a href="{{ route('team.search', ['specialization' => $specialization->id]) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-lg font-medium transition-colors block">
                            <i class="fas fa-search mr-2"></i>Search All
                        </a>
                        <a href="{{ route('team.register') }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded-lg font-medium transition-colors block">
                            <i class="fas fa-user-plus mr-2"></i>Join as {{ $specialization->name }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Developers List -->
            <div class="lg:col-span-3">
                @if($developers->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($developers as $developer)
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
                                    @if($developer->skills)
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
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($developers->hasPages())
                        <div class="mt-8">
                            {{ $developers->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No {{ $specialization->name }}s available</h3>
                        <p class="text-gray-600 mb-6">Be the first to join as a {{ $specialization->name }}!</p>
                        <a href="{{ route('team.register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-user-plus mr-2"></i>Join as {{ $specialization->name }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
