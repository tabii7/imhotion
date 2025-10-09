@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold admin-text-primary">Create New Project</h2>
            <p class="admin-text-secondary mt-2">Add a new project to the platform</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.projects') }}" class="admin-button admin-button-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Projects
            </a>
        </div>
    </div>
</div>

<!-- Create Project Form -->
<div class="admin-card">
    <div class="px-6 py-4 border-b admin-border">
        <h3 class="text-lg font-semibold admin-text-primary">Project Information</h3>
        <p class="text-sm admin-text-secondary">Fill in the project details below</p>
    </div>
    
    <form method="POST" action="{{ route('admin.projects.store') }}">
        @csrf
        <div class="p-6 space-y-6">
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Project Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Topic</label>
                    <input type="text" name="topic" value="{{ old('topic') }}" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    @error('topic')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Client and Status -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Client *</label>
                    <select name="user_id" required class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('user_id') == $client->id ? 'selected' : '' }}>{{ $client->name }} ({{ $client->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Priority</label>
                    <select name="priority" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Delivery Date</label>
                    <input type="date" name="delivery_date" value="{{ old('delivery_date') }}" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    @error('delivery_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Project Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Total Days</label>
                    <input type="number" name="total_days" value="{{ old('total_days') }}" min="1" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    @error('total_days')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Estimated Hours</label>
                    <input type="number" name="estimated_hours" value="{{ old('estimated_hours') }}" min="1" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    @error('estimated_hours')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Developer Assignment -->
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Assign Developer (Optional)</label>
                <select name="assigned_developer_id" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">No developer assigned</option>
                    @foreach($developers as $developer)
                    <option value="{{ $developer->id }}" {{ old('assigned_developer_id') == $developer->id ? 'selected' : '' }}>{{ $developer->name }} - {{ $developer->specialization->name ?? 'No specialization' }}</option>
                    @endforeach
                </select>
                @error('assigned_developer_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Requirements and Notes -->
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Client Requirements</label>
                <textarea name="client_requirements" rows="4" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary" placeholder="Describe the project requirements...">{{ old('client_requirements') }}</textarea>
                @error('client_requirements')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Project Notes</label>
                <textarea name="notes" rows="4" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary" placeholder="Additional project notes...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="px-6 py-4 border-t admin-border flex justify-end space-x-3">
            <a href="{{ route('admin.projects') }}" class="admin-button admin-button-secondary">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" class="admin-button">
                <i class="fas fa-plus mr-2"></i>Create Project
            </button>
        </div>
    </form>
</div>
@endsection
