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
            <a href="{{ route('admin.projects') }}" class="admin-text-secondary hover:admin-text-primary transition-colors duration-200">
                <i class="fas fa-arrow-left text-2xl"></i>
            </a>
            <div>
                <h2 class="text-4xl font-bold admin-text-primary mb-2">Edit Project</h2>
                <p class="admin-text-secondary text-lg">Update project details and settings</p>
            </div>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.projects.show', $project) }}" class="admin-button admin-button-secondary px-6 py-3">
                <i class="fas fa-eye mr-2"></i>View Project
            </a>
        </div>
    </div>
</div>

<!-- Edit Form -->
<div class="max-w-4xl">
    <div class="admin-card p-8">
        <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-2xl font-semibold admin-text-primary mb-6">Basic Information</h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-semibold admin-text-primary mb-3">Project Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}" required
                               class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="topic" class="block text-sm font-semibold admin-text-primary mb-3">Project Topic</label>
                        <input type="text" id="topic" name="topic" value="{{ old('topic', $project->topic) }}"
                               class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('topic') border-red-500 @enderror">
                        @error('topic')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="user_id" class="block text-sm font-semibold admin-text-primary mb-3">Client *</label>
                        <select id="user_id" name="user_id" required
                                class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('user_id') border-red-500 @enderror">
                            <option value="">Select Client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('user_id', $project->user_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} ({{ $client->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="assigned_developer_id" class="block text-sm font-semibold admin-text-primary mb-3">Assigned Developer</label>
                        <select id="assigned_developer_id" name="assigned_developer_id"
                                class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('assigned_developer_id') border-red-500 @enderror">
                            <option value="">Select Developer</option>
                            @foreach($developers as $developer)
                                <option value="{{ $developer->id }}" {{ old('assigned_developer_id', $project->assigned_developer_id) == $developer->id ? 'selected' : '' }}>
                                    {{ $developer->name }} ({{ $developer->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_developer_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Project Details -->
                <div class="space-y-6">
                    <h3 class="text-2xl font-semibold admin-text-primary mb-6">Project Details</h3>
                    
                    <div>
                        <label for="status" class="block text-sm font-semibold admin-text-primary mb-3">Status *</label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status', $project->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                            <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="priority" class="block text-sm font-semibold admin-text-primary mb-3">Priority *</label>
                        <select id="priority" name="priority" required
                                class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('priority') border-red-500 @enderror">
                            <option value="low" {{ old('priority', $project->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $project->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $project->priority) == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="progress" class="block text-sm font-semibold admin-text-primary mb-3">Progress (%)</label>
                        <input type="number" id="progress" name="progress" min="0" max="100" value="{{ old('progress', $project->progress) }}"
                               class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('progress') border-red-500 @enderror">
                        @error('progress')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="total_days" class="block text-sm font-semibold admin-text-primary mb-3">Total Days</label>
                        <input type="number" id="total_days" name="total_days" min="1" value="{{ old('total_days', $project->total_days) }}"
                               class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('total_days') border-red-500 @enderror">
                        @error('total_days')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="total_hours" class="block text-sm font-semibold admin-text-primary mb-3">Total Hours</label>
                        <input type="number" id="total_hours" name="total_hours" min="1" step="0.5" value="{{ old('total_hours', $project->total_hours) }}"
                               class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('total_hours') border-red-500 @enderror">
                        @error('total_hours')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="mt-8 pt-8 border-t admin-border">
                <h3 class="text-2xl font-semibold admin-text-primary mb-6">Timeline</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-semibold admin-text-primary mb-3">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="end_date" class="block text-sm font-semibold admin-text-primary mb-3">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="delivery_date" class="block text-sm font-semibold admin-text-primary mb-3">Delivery Date</label>
                        <input type="date" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', $project->delivery_date ? $project->delivery_date->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('delivery_date') border-red-500 @enderror">
                        @error('delivery_date')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Project Description -->
            <div class="mt-8 pt-8 border-t admin-border">
                <h3 class="text-2xl font-semibold admin-text-primary mb-6">Project Description</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="description" class="block text-sm font-semibold admin-text-primary mb-3">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('description') border-red-500 @enderror">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="notes" class="block text-sm font-semibold admin-text-primary mb-3">Notes</label>
                        <textarea id="notes" name="notes" rows="4"
                                  class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('notes') border-red-500 @enderror">{{ old('notes', $project->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="requirements" class="block text-sm font-semibold admin-text-primary mb-3">Requirements</label>
                        <textarea id="requirements" name="requirements" rows="6"
                                  class="w-full px-4 py-3 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary @error('requirements') border-red-500 @enderror">{{ old('requirements', $project->requirements) }}</textarea>
                        @error('requirements')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="mt-10 pt-8 border-t admin-border">
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.projects') }}" class="admin-button admin-button-secondary px-8 py-3">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="admin-button px-8 py-3">
                        <i class="fas fa-save mr-2"></i>Update Project
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
