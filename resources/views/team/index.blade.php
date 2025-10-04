@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Join Our <span class="text-blue-600">Team</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Connect with top IT professionals and developers. Find your next project or join our network of skilled professionals.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('team.register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                        <i class="fas fa-user-plus mr-2"></i>Join as Developer
                    </a>
                    <a href="{{ route('team.search') }}" class="bg-white hover:bg-gray-50 text-gray-900 border-2 border-gray-300 px-8 py-4 rounded-lg font-semibold text-lg transition-colors">
                        <i class="fas fa-search mr-2"></i>Find Developers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Specializations Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Explore Specializations</h2>
            <p class="text-lg text-gray-600">Choose your area of expertise and connect with like-minded professionals</p>
        </div>

        @foreach($specializations as $category => $specs)
            <div class="mb-16">
                <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">
                    {{ ucfirst(str_replace('_', ' ', $category)) }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($specs as $spec)
                        <a href="{{ route('team.specialization', $spec->slug) }}" 
                           class="group bg-white rounded-xl shadow-sm hover:shadow-lg border border-gray-200 hover:border-blue-300 transition-all duration-300 p-6">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center transition-colors">
                                    <i class="fas fa-{{ $spec->icon }} text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ $spec->name }}
                                    </h4>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $spec->description }}</p>
                            
                            @if($spec->skills && count($spec->skills) > 0)
                                <div class="mb-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($spec->skills, 0, 3) as $skill)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $skill }}
                                            </span>
                                        @endforeach
                                        @if(count($spec->skills) > 3)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                +{{ count($spec->skills) - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center text-blue-600 text-sm font-medium">
                                <span>Explore</span>
                                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Stats Section -->
    <div class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Join Our Growing Network</h2>
                <p class="text-gray-300 text-lg">Be part of a community of skilled IT professionals</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-400 mb-2">37+</div>
                    <div class="text-gray-300">Specializations</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-400 mb-2">100+</div>
                    <div class="text-gray-300">Active Developers</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-400 mb-2">500+</div>
                    <div class="text-gray-300">Projects Completed</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-400 mb-2">98%</div>
                    <div class="text-gray-300">Client Satisfaction</div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-blue-600 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to Start Your Journey?</h2>
            <p class="text-blue-100 text-lg mb-8">Join our team of skilled professionals and start working on exciting projects</p>
            <a href="{{ route('team.register') }}" class="bg-white hover:bg-gray-100 text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg transition-colors inline-flex items-center">
                <i class="fas fa-rocket mr-2"></i>Get Started Now
            </a>
        </div>
    </div>
</div>
@endsection
