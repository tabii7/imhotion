@extends('layouts.administrator')

@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'View system performance and project analytics')

@section('content')
<!-- Reports Overview -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Project Status Distribution -->
    <div class="stats-card">
        <h3 class="text-xl font-bold text-white mb-6">Project Status Distribution</h3>
        @if($reports['project_status_distribution']->count() > 0)
            <div class="space-y-4">
                @foreach($reports['project_status_distribution'] as $status)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 rounded-full mr-3
                                @if($status->status === 'completed') bg-green-500
                                @elseif($status->status === 'in_progress') bg-blue-500
                                @elseif($status->status === 'pending') bg-yellow-500
                                @elseif($status->status === 'on_hold') bg-orange-500
                                @else bg-gray-500 @endif">
                            </div>
                            <span class="text-white font-medium">{{ ucfirst(str_replace('_', ' ', $status->status)) }}</span>
                        </div>
                        <span class="text-gray-300 font-bold">{{ $status->count }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-pie text-white text-2xl"></i>
                </div>
                <p class="text-gray-400 text-lg">No project data available</p>
            </div>
        @endif
    </div>

    <!-- Monthly Project Completion -->
    <div class="stats-card">
        <h3 class="text-xl font-bold text-white mb-6">Monthly Completion</h3>
        @if($reports['monthly_project_completion']->count() > 0)
            <div class="space-y-4">
                @foreach($reports['monthly_project_completion']->take(6) as $month)
                    <div class="flex items-center justify-between">
                        <span class="text-white font-medium">{{ $month->month }}</span>
                        <span class="text-gray-300 font-bold">{{ $month->count }} projects</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar text-white text-2xl"></i>
                </div>
                <p class="text-gray-400 text-lg">No completion data available</p>
            </div>
        @endif
    </div>

    <!-- Developer Workload -->
    <div class="stats-card">
        <h3 class="text-xl font-bold text-white mb-6">Developer Workload</h3>
        @if($reports['developer_workload']->count() > 0)
            <div class="space-y-4">
                @foreach($reports['developer_workload']->take(5) as $developer)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                                {{ substr($developer->name, 0, 1) }}
                            </div>
                            <span class="text-white font-medium">{{ $developer->name }}</span>
                        </div>
                        <span class="text-gray-300 font-bold">{{ $developer->assigned_projects_count }} projects</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <p class="text-gray-400 text-lg">No developer data available</p>
            </div>
        @endif
    </div>
</div>

<!-- Detailed Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Project Performance Chart -->
    <div class="stats-card">
        <h3 class="text-xl font-bold text-white mb-6">Project Performance</h3>
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <span class="text-gray-300">Total Projects</span>
                <span class="text-white font-bold text-xl">{{ $reports['project_status_distribution']->sum('count') }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-300">Completed Projects</span>
                <span class="text-green-400 font-bold text-xl">{{ $reports['project_status_distribution']->where('status', 'completed')->first()->count ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-300">In Progress</span>
                <span class="text-blue-400 font-bold text-xl">{{ $reports['project_status_distribution']->where('status', 'in_progress')->first()->count ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-300">Pending</span>
                <span class="text-yellow-400 font-bold text-xl">{{ $reports['project_status_distribution']->where('status', 'pending')->first()->count ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Team Performance -->
    <div class="stats-card">
        <h3 class="text-xl font-bold text-white mb-6">Team Performance</h3>
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <span class="text-gray-300">Active Developers</span>
                <span class="text-white font-bold text-xl">{{ $reports['developer_workload']->count() }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-300">Average Projects per Developer</span>
                <span class="text-blue-400 font-bold text-xl">{{ $reports['developer_workload']->count() > 0 ? round($reports['developer_workload']->sum('assigned_projects_count') / $reports['developer_workload']->count(), 1) : 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-300">Most Active Developer</span>
                <span class="text-green-400 font-bold text-xl">{{ $reports['developer_workload']->first()->name ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-300">Total Project Assignments</span>
                <span class="text-purple-400 font-bold text-xl">{{ $reports['developer_workload']->sum('assigned_projects_count') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="stats-card">
    <h3 class="text-xl font-bold text-white mb-6">Export Reports</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <button onclick="exportReport('pdf')" class="btn btn-danger justify-center">
            <i class="fas fa-file-pdf"></i>
            Export as PDF
        </button>
        <button onclick="exportReport('excel')" class="btn btn-success justify-center">
            <i class="fas fa-file-excel"></i>
            Export as Excel
        </button>
        <button onclick="exportReport('csv')" class="btn btn-primary justify-center">
            <i class="fas fa-file-csv"></i>
            Export as CSV
        </button>
    </div>
</div>

<script>
function exportReport(format) {
    // Create a form to submit the export request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/administrator/reports/export';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);
    
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = format;
    form.appendChild(formatInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endsection