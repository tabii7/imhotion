@extends('layouts.dashboard')

@section('page-title', 'Create New Project')

@section('content')
<div class="p-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('dashboard.projects') }}" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-white">Create New Project</h1>
                <p class="text-gray-300">Start a new development project</p>
            </div>
        </div>
    </div>

    <!-- Create Project Form -->
    <div class="max-w-2xl">
        <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-xl p-8">
            <form action="{{ route('dashboard.projects.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Project Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-white mb-2">Project Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors"
                           placeholder="Enter project name"
                           style="color: white !important;"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-white mb-2">Description</label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors resize-none"
                              placeholder="Describe your project requirements and goals"
                              style="color: white !important;"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estimated Hours -->
                <div>
                    <label for="estimated_hours" class="block text-sm font-medium text-white mb-2">Estimated Hours</label>
                    <input type="number" 
                           id="estimated_hours" 
                           name="estimated_hours" 
                           value="{{ old('estimated_hours') }}"
                           min="1"
                           class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors"
                           placeholder="Enter estimated hours"
                           style="color: white !important;"
                           required>
                    @error('estimated_hours')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-white mb-2">Priority</label>
                    <select id="priority" 
                            name="priority"
                            class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors"
                            style="color: white !important;"
                            required>
                        <option value="">Select priority</option>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deadline -->
                <div>
                    <label for="deadline" class="block text-sm font-medium text-white mb-2">Deadline (Optional)</label>
                    <input type="date" 
                           id="deadline" 
                           name="deadline" 
                           value="{{ old('deadline') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors"
                           style="color: white !important;">
                    @error('deadline')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center gap-4 pt-6">
                    <button type="submit" 
                            class="bg-brand-primary hover:bg-brand-primary/90 text-white px-8 py-3 rounded-lg font-medium transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Project
                    </button>
                    
                    <a href="{{ route('dashboard.projects') }}" 
                       class="bg-slate-700/50 hover:bg-slate-700 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
