@extends('layouts.admin')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold admin-text-primary">Project Management</h2>
            <p class="admin-text-secondary mt-2">Monitor and manage all projects in the platform</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.projects.create') }}" class="admin-button">
                <i class="fas fa-plus mr-2"></i>New Project
            </a>
            <button class="admin-button admin-button-secondary" onclick="exportProjects()">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <button class="admin-button admin-button-secondary" onclick="showAnalytics()">
                <i class="fas fa-chart-bar mr-2"></i>Analytics
            </button>
        </div>
    </div>
</div>

<!-- Project Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-folder text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Total Projects</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\Project::count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Completed</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\Project::where('status', 'completed')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">In Progress</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\Project::where('status', 'in_progress')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="admin-stats-card p-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-pause-circle text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium admin-text-secondary">Pending</p>
                <p class="text-2xl font-bold admin-text-primary">{{ \App\Models\Project::where('status', 'pending')->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="admin-card mb-6">
    <div class="p-6">
        <form id="projectFilters" method="GET" action="{{ route('admin.projects') }}">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..." class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Client</label>
                    <select name="client" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                        <option value="">All Clients</option>
                        @foreach(\App\Models\User::where('role', 'client')->get() as $client)
                        <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium admin-text-primary mb-2">Developer</label>
                    <select name="developer" class="w-full px-3 py-2 border admin-border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 admin-bg-secondary admin-text-primary">
                        <option value="">All Developers</option>
                        @foreach(\App\Models\User::where('role', 'developer')->get() as $developer)
                        <option value="{{ $developer->id }}" {{ request('developer') == $developer->id ? 'selected' : '' }}>{{ $developer->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="admin-button flex-1">
                        <i class="fas fa-filter mr-2"></i>Apply
                    </button>
                    <button type="button" onclick="clearFilters()" class="admin-button admin-button-secondary">
                        <i class="fas fa-times mr-2"></i>Clear
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Projects Table -->
<div class="admin-card">
    <div class="px-6 py-4 border-b admin-border">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold admin-text-primary">All Projects</h3>
                <p class="text-sm admin-text-secondary">Manage and monitor project progress</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="toggleView('grid')" class="px-3 py-1 border admin-border rounded-md text-sm admin-text-primary hover:admin-bg-secondary transition-colors">
                    <i class="fas fa-th mr-1"></i>Grid
                </button>
                <button onclick="toggleView('table')" class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">
                    <i class="fas fa-list mr-1"></i>Table
                </button>
            </div>
        </div>
    </div>
    
    <!-- Table View -->
    <div id="tableView" class="overflow-x-auto">
        <table class="w-full">
            <thead class="admin-bg-secondary">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Project Details</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Developer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Timeline</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium admin-text-secondary uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y admin-border">
                @php
                    $projects = \App\Models\Project::with(['user', 'assignedDeveloper', 'assignedAdministrator'])
                        ->when(request('search'), function($query) {
                            $query->where('name', 'like', '%' . request('search') . '%')
                                  ->orWhere('topic', 'like', '%' . request('search') . '%');
                        })
                        ->when(request('status'), function($query) {
                            $query->where('status', request('status'));
                        })
                        ->when(request('client'), function($query) {
                            $query->where('user_id', request('client'));
                        })
                        ->when(request('developer'), function($query) {
                            $query->where('assigned_developer_id', request('developer'));
                        })
                        ->latest()
                        ->paginate(15);
                @endphp
                
                @foreach($projects as $project)
                <tr class="hover:admin-bg-secondary transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                    <i class="fas fa-folder text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-semibold admin-text-primary">{{ $project->name }}</div>
                                <div class="text-sm admin-text-secondary">{{ $project->topic }}</div>
                                @if($project->priority)
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        @if($project->priority === 'high') bg-red-100 text-red-800
                                        @elseif($project->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        <i class="fas fa-flag mr-1"></i>{{ ucfirst($project->priority) }} Priority
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">{{ substr($project->user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium admin-text-primary">{{ $project->user->name }}</div>
                                <div class="text-xs admin-text-secondary">{{ $project->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($project->assignedDeveloper)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">{{ substr($project->assignedDeveloper->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium admin-text-primary">{{ $project->assignedDeveloper->name }}</div>
                                <div class="text-xs admin-text-secondary">{{ $project->assignedDeveloper->specialization->name ?? 'No specialization' }}</div>
                            </div>
                        </div>
                        @else
                        <div class="text-center">
                            <span class="text-sm admin-text-secondary">Unassigned</span>
                            <a href="{{ route('admin.projects.assign', $project->id) }}" class="block mx-auto mt-1 text-blue-600 hover:text-blue-900 transition-colors">
                                <i class="fas fa-user-plus text-xs"></i>
                            </a>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="admin-badge
                            @if($project->status === 'completed') bg-gradient-to-r from-green-500 to-green-600
                            @elseif($project->status === 'in_progress') bg-gradient-to-r from-blue-500 to-blue-600
                            @elseif($project->status === 'pending') bg-gradient-to-r from-yellow-500 to-yellow-600
                            @else bg-gradient-to-r from-red-500 to-red-600 @endif">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                        @if($project->completed_at)
                        <div class="text-xs admin-text-secondary mt-1">
                            Completed {{ $project->completed_at->format('M d, Y') }}
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm admin-text-primary">
                            @if($project->start_date)
                            <div>Start: {{ $project->start_date->format('M d, Y') }}</div>
                            @endif
                            @if($project->end_date)
                            <div>End: {{ $project->end_date->format('M d, Y') }}</div>
                            @endif
                            @if($project->delivery_date)
                            <div>Delivery: {{ $project->delivery_date->format('M d, Y') }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                @php
                                    $progress = 0;
                                    if($project->total_days > 0) {
                                        $progress = min(100, ($project->days_used / $project->total_days) * 100);
                                    }
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <span class="text-sm admin-text-secondary">{{ number_format($progress, 0) }}%</span>
                        </div>
                        @if($project->total_days)
                        <div class="text-xs admin-text-secondary mt-1">
                            {{ $project->days_used }}/{{ $project->total_days }} days
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-1">
                            <a href="{{ route('admin.projects.show', $project->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors p-1" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.projects.edit', $project->id) }}" class="text-yellow-600 hover:text-yellow-900 transition-colors p-1" title="Edit Project">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$project->assignedDeveloper)
                            <a href="{{ route('admin.projects.assign', $project->id) }}" class="text-green-600 hover:text-green-900 transition-colors p-1" title="Assign Developer">
                                <i class="fas fa-user-plus"></i>
                            </a>
                            @endif
                            <button onclick="deleteProject({{ $project->id }})" class="text-red-600 hover:text-red-900 transition-colors p-1" title="Delete Project">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Grid View -->
    <div id="gridView" class="hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($projects as $project)
            <div class="admin-card hover:shadow-lg transition-all duration-300">
                <div class="p-6">
                    <!-- Project Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                    <i class="fas fa-folder text-white"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold admin-text-primary">{{ $project->name }}</h3>
                                <p class="text-xs admin-text-secondary">{{ $project->topic }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-1">
                            <a href="{{ route('admin.projects.show', $project->id) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.projects.edit', $project->id) }}" class="text-yellow-600 hover:text-yellow-900 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Status and Priority -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="admin-badge
                            @if($project->status === 'completed') bg-gradient-to-r from-green-500 to-green-600
                            @elseif($project->status === 'in_progress') bg-gradient-to-r from-blue-500 to-blue-600
                            @elseif($project->status === 'pending') bg-gradient-to-r from-yellow-500 to-yellow-600
                            @else bg-gradient-to-r from-red-500 to-red-600 @endif">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                        @if($project->priority)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                            @if($project->priority === 'high') bg-red-100 text-red-800
                            @elseif($project->priority === 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ ucfirst($project->priority) }}
                        </span>
                        @endif
                    </div>
                    
                    <!-- Client and Developer -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-6 w-6">
                                <div class="h-6 w-6 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">{{ substr($project->user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-2">
                                <div class="text-xs font-medium admin-text-primary">{{ $project->user->name }}</div>
                                <div class="text-xs admin-text-secondary">Client</div>
                            </div>
                        </div>
                        
                        @if($project->assignedDeveloper)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-6 w-6">
                                <div class="h-6 w-6 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                    <span class="text-xs font-bold text-white">{{ substr($project->assignedDeveloper->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-2">
                                <div class="text-xs font-medium admin-text-primary">{{ $project->assignedDeveloper->name }}</div>
                                <div class="text-xs admin-text-secondary">Developer</div>
                            </div>
                        </div>
                        @else
                        <div class="text-center">
                            <span class="text-xs admin-text-secondary">No developer assigned</span>
                            <a href="{{ route('admin.projects.assign', $project->id) }}" class="block mx-auto mt-1 text-green-600 hover:text-green-900 transition-colors">
                                <i class="fas fa-user-plus text-xs"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Progress -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs admin-text-secondary">Progress</span>
                            <span class="text-xs admin-text-primary">{{ number_format($progress, 0) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                        @if($project->total_days)
                        <div class="text-xs admin-text-secondary mt-1">
                            {{ $project->days_used }}/{{ $project->total_days }} days used
                        </div>
                        @endif
                    </div>
                    
                    <!-- Timeline -->
                    @if($project->start_date || $project->end_date)
                    <div class="text-xs admin-text-secondary mb-4">
                        @if($project->start_date)
                        <div>Start: {{ $project->start_date->format('M d, Y') }}</div>
                        @endif
                        @if($project->end_date)
                        <div>End: {{ $project->end_date->format('M d, Y') }}</div>
                        @endif
                    </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.projects.show', $project->id) }}" class="flex-1 admin-button admin-button-secondary text-xs">
                            <i class="fas fa-eye mr-1"></i>View
                        </a>
                        <a href="{{ route('admin.projects.edit', $project->id) }}" class="flex-1 admin-button text-xs">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t admin-border">
        <div class="flex items-center justify-between">
            <div class="text-sm admin-text-secondary">
                Showing {{ $projects->firstItem() }} to {{ $projects->lastItem() }} of {{ $projects->total() }} results
            </div>
            <div class="flex space-x-2">
                @if($projects->previousPageUrl())
                <a href="{{ $projects->previousPageUrl() }}" class="px-3 py-1 border admin-border rounded-md text-sm admin-text-primary hover:admin-bg-secondary transition-colors">
                    Previous
                </a>
                @endif
                
                @for($i = 1; $i <= $projects->lastPage(); $i++)
                <a href="{{ $projects->url($i) }}" class="px-3 py-1 {{ $projects->currentPage() == $i ? 'bg-blue-600 text-white' : 'border admin-border admin-text-primary hover:admin-bg-secondary' }} rounded-md text-sm transition-colors">
                    {{ $i }}
                </a>
                @endfor
                
                @if($projects->nextPageUrl())
                <a href="{{ $projects->nextPageUrl() }}" class="px-3 py-1 border admin-border rounded-md text-sm admin-text-primary hover:admin-bg-secondary transition-colors">
                    Next
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// View Toggle
function toggleView(view) {
    const tableView = document.getElementById('tableView');
    const gridView = document.getElementById('gridView');
    const tableBtn = document.querySelector('button[onclick="toggleView(\'table\')"]');
    const gridBtn = document.querySelector('button[onclick="toggleView(\'grid\')"]');
    
    if (view === 'table') {
        tableView.classList.remove('hidden');
        gridView.classList.add('hidden');
        tableBtn.classList.add('bg-blue-600', 'text-white');
        tableBtn.classList.remove('border', 'admin-border', 'admin-text-primary');
        gridBtn.classList.remove('bg-blue-600', 'text-white');
        gridBtn.classList.add('border', 'admin-border', 'admin-text-primary');
    } else {
        tableView.classList.add('hidden');
        gridView.classList.remove('hidden');
        gridBtn.classList.add('bg-blue-600', 'text-white');
        gridBtn.classList.remove('border', 'admin-border', 'admin-text-primary');
        tableBtn.classList.remove('bg-blue-600', 'text-white');
        tableBtn.classList.add('border', 'admin-border', 'admin-text-primary');
    }
}

// Clear Filters
function clearFilters() {
    document.getElementById('projectFilters').reset();
    window.location.href = '{{ route("admin.projects") }}';
}

// Delete Project
function deleteProject(projectId) {
    if (confirm('Are you sure you want to delete this project? This action cannot be undone.')) {
        fetch(`/admin/admin-projects/${projectId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error deleting project');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting project');
        });
    }
}

function exportProjects() {
    // Implement export functionality
    alert('Export functionality will be implemented');
}

function showAnalytics() {
    // Implement analytics functionality
    alert('Analytics functionality will be implemented');
}
</script>
@endsection
