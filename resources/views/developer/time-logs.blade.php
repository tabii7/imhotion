@extends('layouts.developer')

@section('page-title', 'Time Logs')

@section('content')
    <!-- Time Logs Table -->
    <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
        <div class="px-8 py-6 border-b border-gray-700/30">
            <h2 class="text-2xl font-bold text-white">Your Time Logs</h2>
            <p class="text-gray-300 mt-2">Track your time spent on projects</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700/30">
                <thead class="bg-gray-800/50">
                    <tr>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Project</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Hours</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Description</th>
                        <th class="px-8 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-transparent divide-y divide-gray-700/30">
                    @forelse($timeLogs as $log)
                        <tr class="hover:bg-white/5 transition-all duration-200">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="text-sm font-semibold text-white">{{ $log->project->name }}</div>
                                <div class="text-sm text-gray-300">{{ $log->project->topic }}</div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-sm text-white">
                                {{ \Carbon\Carbon::parse($log->work_date)->format('M d, Y') }}
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-sm text-white">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg">
                                    {{ $log->hours_spent }} hours
                                </span>
                            </td>
                            <td class="px-8 py-6 text-sm text-gray-300">
                                {{ Str::limit($log->description, 50) }}
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-sm font-medium">
                                <button onclick="editTimeLog({{ $log->id }})" class="text-blue-400 hover:text-blue-300 mr-4 transform hover:scale-110 transition-all duration-200">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteTimeLog({{ $log->id }})" class="text-red-400 hover:text-red-300 transform hover:scale-110 transition-all duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center">
                                <div class="w-20 h-20 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-clock text-white text-3xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-white mb-3">No time logs found</h3>
                                <p class="text-gray-400 text-lg">Start logging your time on projects to track your work.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($timeLogs->hasPages())
        <div class="mt-8">
            {{ $timeLogs->links() }}
        </div>
    @endif
</div>

<script>
function editTimeLog(logId) {
    // Implementation for editing time log
    alert('Edit time log functionality coming soon!');
}

function deleteTimeLog(logId) {
    if (confirm('Are you sure you want to delete this time log?')) {
        // Implementation for deleting time log
        alert('Delete time log functionality coming soon!');
    }
}
</script>
@endsection
