@extends('layouts.admin')

@section('page-title', 'Create Team')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('admin.teams.index') }}" class="mr-4 p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                    <i class="fas fa-arrow-left text-white"></i>
                </a>
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Create Team</h1>
                    <p class="text-gray-300">Set up a new team and assign members</p>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <form action="{{ route('admin.teams.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Team Information -->
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                    <div class="px-8 py-6 border-b border-gray-700/30">
                        <h3 class="text-2xl font-bold text-white">Team Information</h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Team Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('name')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="team_lead_id" class="block text-sm font-medium text-gray-300 mb-2">Team Lead</label>
                            <select id="team_lead_id" name="team_lead_id"
                                class="w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select a team lead</option>
                                @foreach($developers as $developer)
                                    <option value="{{ $developer->id }}" {{ old('team_lead_id') == $developer->id ? 'selected' : '' }}>
                                        {{ $developer->name }} ({{ $developer->specialization->name ?? 'No specialization' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('team_lead_id')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Specializations</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($specializations as $specialization)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="specializations[]" value="{{ $specialization->id }}" 
                                            {{ in_array($specialization->id, old('specializations', [])) ? 'checked' : '' }}
                                            class="rounded border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                                        <span class="ml-2 text-white">{{ $specialization->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('specializations')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                    <div class="px-8 py-6 border-b border-gray-700/30">
                        <h3 class="text-2xl font-bold text-white">Team Members</h3>
                        <p class="text-gray-400 mt-2">Select developers to add to this team</p>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($developers as $developer)
                                <label class="flex items-center p-4 bg-white/5 rounded-xl hover:bg-white/10 transition-colors cursor-pointer">
                                    <input type="checkbox" name="members[]" value="{{ $developer->id }}"
                                        {{ in_array($developer->id, old('members', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-600 text-blue-600 focus:ring-blue-500 focus:ring-offset-0">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="text-white font-medium">{{ $developer->name }}</div>
                                                <div class="text-gray-400 text-sm">{{ $developer->specialization->name ?? 'No specialization' }}</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-gray-400 text-sm">{{ ucfirst($developer->experience_level) }}</div>
                                                <div class="text-gray-500 text-xs">
                                                    {{ $developer->assignedProjects->count() }} projects
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('members')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.teams.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Create Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
