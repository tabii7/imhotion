@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold admin-text-primary">User Details</h2>
            <p class="admin-text-secondary mt-2">View and manage user information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users') }}" class="admin-button admin-button-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
            </a>
            <a href="{{ route('admin.users.edit', $user) }}" class="admin-button">
                <i class="fas fa-edit mr-2"></i>Edit User
            </a>
        </div>
    </div>
</div>

<!-- User Profile Card -->
<div class="admin-card mb-6">
    <div class="p-6">
        <div class="flex items-start space-x-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                @if($user->avatar)
                    <img class="h-24 w-24 rounded-full object-cover" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                @else
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            
            <!-- User Info -->
            <div class="flex-1">
                <div class="flex items-center space-x-4 mb-4">
                    <h3 class="text-2xl font-bold admin-text-primary">{{ $user->name }}</h3>
                    <span class="admin-badge
                        @if($user->role === 'admin') bg-gradient-to-r from-red-500 to-red-600
                        @elseif($user->role === 'developer') bg-gradient-to-r from-blue-500 to-blue-600
                        @elseif($user->role === 'client') bg-gradient-to-r from-green-500 to-green-600
                        @else bg-gradient-to-r from-gray-500 to-gray-600 @endif">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($user->is_available ?? true) bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        <i class="fas fa-circle mr-1 text-xs"></i>
                        {{ ($user->is_available ?? true) ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm admin-text-secondary">Email</p>
                        <p class="text-lg admin-text-primary">{{ $user->email }}</p>
                    </div>
                    @if($user->phone)
                    <div>
                        <p class="text-sm admin-text-secondary">Phone</p>
                        <p class="text-lg admin-text-primary">{{ $user->phone }}</p>
                    </div>
                    @endif
                    @if($user->country)
                    <div>
                        <p class="text-sm admin-text-secondary">Country</p>
                        <p class="text-lg admin-text-primary">{{ $user->country }}</p>
                    </div>
                    @endif
                    @if($user->specialization)
                    <div>
                        <p class="text-sm admin-text-secondary">Specialization</p>
                        <p class="text-lg admin-text-primary">{{ $user->specialization }}</p>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4">
                    <p class="text-sm admin-text-secondary">Member since</p>
                    <p class="text-lg admin-text-primary">{{ $user->created_at->format('F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Details Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Skills -->
    @if($user->skills && (is_array($user->skills) ? count($user->skills) > 0 : $user->skills))
    <div class="admin-card">
        <div class="px-6 py-4 border-b admin-border">
            <h3 class="text-lg font-semibold admin-text-primary">Skills</h3>
        </div>
        <div class="p-6">
            @if(is_array($user->skills) && count($user->skills) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($user->skills as $skill)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $skill }}
                        </span>
                    @endforeach
                </div>
            @elseif(is_string($user->skills) && !empty($user->skills))
                <p class="admin-text-primary">{{ $user->skills }}</p>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Account Information -->
    <div class="admin-card">
        <div class="px-6 py-4 border-b admin-border">
            <h3 class="text-lg font-semibold admin-text-primary">Account Information</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between">
                <span class="admin-text-secondary">User ID</span>
                <span class="admin-text-primary font-mono">{{ $user->id }}</span>
            </div>
            <div class="flex justify-between">
                <span class="admin-text-secondary">Email Verified</span>
                <span class="admin-text-primary">
                    @if($user->email_verified_at)
                        <i class="fas fa-check-circle text-green-500"></i> Yes
                    @else
                        <i class="fas fa-times-circle text-red-500"></i> No
                    @endif
                </span>
            </div>
            <div class="flex justify-between">
                <span class="admin-text-secondary">Last Updated</span>
                <span class="admin-text-primary">{{ $user->updated_at->diffForHumans() }}</span>
            </div>
            <div class="flex justify-between">
                <span class="admin-text-secondary">Account Status</span>
                <span class="admin-text-primary">
                    @if($user->is_available ?? true)
                        <i class="fas fa-check-circle text-green-500"></i> Active
                    @else
                        <i class="fas fa-pause-circle text-red-500"></i> Inactive
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="admin-card mt-6">
    <div class="px-6 py-4 border-b admin-border">
        <h3 class="text-lg font-semibold admin-text-primary">Quick Actions</h3>
    </div>
    <div class="p-6">
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.users.edit', $user) }}" class="admin-button">
                <i class="fas fa-edit mr-2"></i>Edit User
            </a>
            
            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="admin-button {{ ($user->is_available ?? true) ? 'admin-button-danger' : 'admin-button-success' }}">
                    <i class="fas fa-{{ ($user->is_available ?? true) ? 'pause' : 'play' }} mr-2"></i>
                    {{ ($user->is_available ?? true) ? 'Deactivate' : 'Activate' }} User
                </button>
            </form>
            
            @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-button admin-button-danger">
                    <i class="fas fa-trash mr-2"></i>Delete User
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
