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
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold admin-text-primary">User Management</h2>
            <p class="admin-text-secondary mt-2">Manage all users and their roles in the platform</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.create') }}" class="admin-button">
                <i class="fas fa-plus mr-2"></i>Add User
            </a>
            <button class="admin-button admin-button-secondary">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="admin-card mb-6">
    <form method="GET" action="{{ route('admin.users') }}" class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
            </div>
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Role</label>
                <select name="role" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="developer" {{ request('role') === 'developer' ? 'selected' : '' }}>Developer</option>
                    <option value="client" {{ request('role') === 'client' ? 'selected' : '' }}>Client</option>
                    <option value="administrator" {{ request('role') === 'administrator' ? 'selected' : '' }}>Administrator</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium admin-text-primary mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="admin-button flex-1">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
                <a href="{{ route('admin.users') }}" class="admin-button admin-button-secondary">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="admin-card">
    <div class="px-6 py-4 border-b admin-border">
        <h3 class="text-lg font-semibold admin-text-primary">All Users</h3>
        <p class="text-sm admin-text-secondary">Manage user accounts and permissions</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="admin-bg-secondary">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Last Active</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y admin-border">
                @forelse($users as $user)
                <tr class="hover:admin-bg-secondary transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                @if($user->avatar)
                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                        <span class="text-sm font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium admin-text-primary">{{ $user->name }}</div>
                                <div class="text-sm admin-text-secondary">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="admin-badge
                            @if($user->role === 'admin') bg-gradient-to-r from-red-500 to-red-600
                            @elseif($user->role === 'developer') bg-gradient-to-r from-blue-500 to-blue-600
                            @elseif($user->role === 'client') bg-gradient-to-r from-green-500 to-green-600
                            @else bg-gradient-to-r from-gray-500 to-gray-600 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->is_available ?? true) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ ($user->is_available ?? true) ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm admin-text-secondary">
                        {{ $user->updated_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-{{ ($user->is_available ?? true) ? 'orange' : 'green' }}-600 hover:text-{{ ($user->is_available ?? true) ? 'orange' : 'green' }}-900 transition-colors" title="{{ ($user->is_available ?? true) ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-{{ ($user->is_available ?? true) ? 'pause' : 'play' }}"></i>
                                </button>
                            </form>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center admin-text-secondary">
                        <div class="flex flex-col items-center py-8">
                            <i class="fas fa-users text-4xl admin-text-secondary mb-4"></i>
                            <p class="text-lg font-medium">No users found</p>
                            <p class="text-sm">Try adjusting your search or filter criteria</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($users->hasPages())
    <div class="px-6 py-4 border-t admin-border">
        <div class="flex items-center justify-between">
            <div class="text-sm admin-text-secondary">
                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
            </div>
            <div class="flex space-x-2">
                @if($users->onFirstPage())
                    <span class="px-3 py-1 border admin-border rounded-md text-sm admin-text-secondary cursor-not-allowed">
                        Previous
                    </span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 border admin-border rounded-md text-sm admin-text-primary hover:admin-bg-secondary transition-colors">
                        Previous
                    </a>
                @endif
                
                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 border admin-border rounded-md text-sm admin-text-primary hover:admin-bg-secondary transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 border admin-border rounded-md text-sm admin-text-primary hover:admin-bg-secondary transition-colors">
                        Next
                    </a>
                @else
                    <span class="px-3 py-1 border admin-border rounded-md text-sm admin-text-secondary cursor-not-allowed">
                        Next
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Total Users</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\User::count() }}</p>
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
                <p class="text-sm font-medium admin-text-secondary">Active Users</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\User::where('is_available', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-code text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Developers</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\User::where('role', 'developer')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-tie text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Clients</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\User::where('role', 'client')->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
