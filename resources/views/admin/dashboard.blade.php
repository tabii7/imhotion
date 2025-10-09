@extends('layouts.admin')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <div class="admin-card p-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold admin-text-primary mb-2">Welcome to Imhotion Admin</h1>
                <p class="text-lg admin-text-secondary">Manage your projects, teams, and users from this central dashboard</p>
            </div>
            <div class="flex space-x-3">
                <button class="admin-button">
                    <i class="fas fa-plus mr-2"></i>Create New Project
                </button>
                <button class="admin-button admin-button-secondary">
                    <i class="fas fa-users mr-2"></i>Manage Users
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Projects Card -->
    <div class="admin-card hover:shadow-lg transition-all duration-300 cursor-pointer">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-folder text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold admin-text-primary">Projects</h3>
                    <p class="text-sm admin-text-secondary">Manage all projects</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Card -->
    <div class="admin-card hover:shadow-lg transition-all duration-300 cursor-pointer">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold admin-text-primary">Users</h3>
                    <p class="text-sm admin-text-secondary">Manage users and roles</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Teams Card -->
    <div class="admin-card hover:shadow-lg transition-all duration-300 cursor-pointer">
        <div class="p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold admin-text-primary">Teams</h3>
                    <p class="text-sm admin-text-secondary">Manage teams</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium admin-text-secondary">Total Users</p>
                <p class="text-2xl font-semibold admin-text-primary">{{ \App\Models\User::count() }}</p>
                <p class="text-xs admin-text-secondary">All registered users</p>
            </div>
        </div>
    </div>

    <!-- New Users This Month -->
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-user-plus text-green-600"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium admin-text-secondary">New Users This Month</p>
                <p class="text-2xl font-semibold admin-text-primary">{{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}</p>
                <p class="text-xs admin-text-secondary">New registrations</p>
            </div>
        </div>
    </div>

    <!-- Active Projects -->
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-lg bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-folder text-purple-600"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium admin-text-secondary">Active Projects</p>
                <p class="text-2xl font-semibold admin-text-primary">{{ \App\Models\Project::where('status', 'in_progress')->count() }}</p>
                <p class="text-xs admin-text-secondary">Currently in progress</p>
            </div>
        </div>
    </div>

    <!-- Revenue This Month -->
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 rounded-lg bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-orange-600"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium admin-text-secondary">Revenue This Month</p>
                <p class="text-2xl font-semibold admin-text-primary">${{ number_format(rand(15000, 25000)) }}</p>
                <p class="text-xs admin-text-secondary">Monthly earnings</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Revenue Chart -->
    <div class="admin-card shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
            <p class="text-sm text-gray-500">Monthly revenue for the past 6 months</p>
        </div>
        <div class="p-6">
            <div class="h-64 flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-chart-line text-4xl text-blue-400 mb-4"></i>
                    <p class="text-gray-500 font-medium">Revenue Analytics</p>
                    <p class="text-sm text-gray-400">Interactive charts coming soon</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Status -->
    <div class="admin-card shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Project Status</h3>
            <p class="text-sm text-gray-500">Current project distribution</p>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Completed</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ \App\Models\Project::where('status', 'completed')->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">In Progress</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ \App\Models\Project::where('status', 'in_progress')->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Pending</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ \App\Models\Project::where('status', 'pending')->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                        <span class="text-sm font-medium text-gray-700">Cancelled</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900">{{ \App\Models\Project::where('status', 'cancelled')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Users -->
    <div class="admin-card shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
            <p class="text-sm text-gray-500">Latest registered users</p>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach(\App\Models\User::latest()->take(5)->get() as $user)
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <span class="text-sm font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                    <div class="ml-auto">
                        <span class="admin-badge
                            @if($user->role === 'admin') bg-gradient-to-r from-red-500 to-red-600
                            @elseif($user->role === 'developer') bg-gradient-to-r from-blue-500 to-blue-600
                            @elseif($user->role === 'client') bg-gradient-to-r from-green-500 to-green-600
                            @else bg-gradient-to-r from-gray-500 to-gray-600 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Projects -->
    <div class="admin-card shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Projects</h3>
            <p class="text-sm text-gray-500">Latest project activities</p>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach(\App\Models\Project::latest()->take(5)->get() as $project)
            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ $project->name }}</p>
                        <p class="text-sm text-gray-500">{{ $project->user->name }}</p>
                    </div>
                    <div class="ml-4">
                        <span class="admin-badge
                            @if($project->status === 'completed') bg-gradient-to-r from-green-500 to-green-600
                            @elseif($project->status === 'in_progress') bg-gradient-to-r from-blue-500 to-blue-600
                            @elseif($project->status === 'pending') bg-gradient-to-r from-yellow-500 to-yellow-600
                            @else bg-gradient-to-r from-red-500 to-red-600 @endif">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection