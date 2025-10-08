@extends('layouts.admin')

@section('page-title', 'Project Report')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Project Report</h1>
                    <p class="text-gray-300">{{ $project->name }}</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.project-reports.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Reports
                    </a>
                    <a href="{{ route('admin.custom-projects.show', $project) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-eye mr-2"></i>
                        View Project
                    </a>
                </div>
            </div>
        </div>

        <!-- Project Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Project Details -->
            <div class="lg:col-span-2">
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8">
                    <h3 class="text-2xl font-bold text-white mb-6">Project Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-300">Project Name</label>
                            <p class="text-white text-lg font-semibold">{{ $project->name }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-300">Status</label>
                            <div class="mt-2">
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($project->status === 'in_progress') bg-blue-500/20 text-blue-300
                                    @elseif($project->status === 'completed') bg-green-500/20 text-green-300
                                    @elseif($project->status === 'on_hold') bg-yellow-500/20 text-yellow-300
                                    @elseif($project->status === 'cancelled') bg-red-500/20 text-red-300
                                    @elseif($project->status === 'finalized') bg-purple-500/20 text-purple-300
                                    @else bg-gray-500/20 text-gray-300 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-gray-300">Client</label>
                            <p class="text-white">{{ $project->user->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $project->user->email }}</p>
                        </div>
                        
                        @if($project->assignedDeveloper)
                            <div>
                                <label class="text-sm font-medium text-gray-300">Assigned Developer</label>
                                <p class="text-white">{{ $project->assignedDeveloper->name }}</p>
                                <p class="text-gray-400 text-sm">{{ $project->assignedDeveloper->email }}</p>
                            </div>
                        @endif
                        
                        @if($project->delivery_date)
                            <div>
                                <label class="text-sm font-medium text-gray-300">Delivery Date</label>
                                <p class="text-white">{{ \Carbon\Carbon::parse($project->delivery_date)->format('M d, Y') }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label class="text-sm font-medium text-gray-300">Created</label>
                            <p class="text-white">{{ $project->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    @if($project->topic)
                        <div class="mt-6">
                            <label class="text-sm font-medium text-gray-300">Description</label>
                            <p class="text-gray-300 mt-2">{{ $project->topic }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Metrics -->
            <div class="space-y-6">
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-6">
                    <h3 class="text-xl font-bold text-white mb-4">Key Metrics</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-300">Total Hours</span>
                            <span class="text-white font-bold">{{ $metrics['total_hours'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Documents</span>
                            <span class="text-white font-bold">{{ $metrics['total_documents'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Activities</span>
                            <span class="text-white font-bold">{{ $metrics['total_activities'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Completion Rate</span>
                            <span class="text-white font-bold">{{ $metrics['completion_rate'] }}%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-300">Days Since Start</span>
                            <span class="text-white font-bold">{{ $metrics['days_since_start'] }}</span>
                        </div>
                        @if($metrics['days_until_deadline'] !== null)
                            <div class="flex justify-between">
                                <span class="text-gray-300">Days Until Deadline</span>
                                <span class="text-white font-bold {{ $metrics['days_until_deadline'] < 0 ? 'text-red-400' : ($metrics['days_until_deadline'] < 7 ? 'text-yellow-400' : 'text-green-400') }}">
                                    {{ $metrics['days_until_deadline'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Progress Chart -->
                @if($metrics['completion_rate'] > 0)
                    <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-6">
                        <h3 class="text-xl font-bold text-white mb-4">Progress</h3>
                        <div class="text-center">
                            <div class="relative w-32 h-32 mx-auto mb-4">
                                <svg class="w-32 h-32 transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-700" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                    <path class="text-blue-500" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="{{ $metrics['completion_rate'] }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white">{{ $metrics['completion_rate'] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Project Timeline -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8 mb-8">
            <h3 class="text-2xl font-bold text-white mb-6">Project Timeline</h3>
            <div class="space-y-6">
                @forelse($timeline as $event)
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                @if($event['type'] === 'created') bg-green-500
                                @elseif($event['type'] === 'assigned') bg-blue-500
                                @elseif($event['type'] === 'activity') bg-purple-500
                                @elseif($event['type'] === 'progress') bg-yellow-500
                                @else bg-gray-500 @endif">
                                <i class="fas 
                                    @if($event['type'] === 'created') fa-plus
                                    @elseif($event['type'] === 'assigned') fa-user-plus
                                    @elseif($event['type'] === 'activity') fa-tasks
                                    @elseif($event['type'] === 'progress') fa-chart-line
                                    @else fa-circle @endif text-white text-sm">
                                </i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="text-white font-semibold">{{ $event['title'] }}</h4>
                                <span class="text-gray-400 text-sm">{{ \Carbon\Carbon::parse($event['date'])->format('M d, Y H:i') }}</span>
                            </div>
                            <p class="text-gray-300 mb-1">{{ $event['description'] }}</p>
                            <p class="text-gray-400 text-sm">by {{ $event['user'] }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-8">No timeline events available</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Activities -->
        @if($project->activities->count() > 0)
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8 mb-8">
                <h3 class="text-2xl font-bold text-white mb-6">Recent Activities</h3>
                <div class="space-y-4">
                    @foreach($project->activities->take(10) as $activity)
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        {{ substr($activity->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="text-white font-semibold">{{ $activity->user->name }}</h4>
                                        <p class="text-gray-400 text-sm">{{ ucfirst(str_replace('_', ' ', $activity->type)) }}</p>
                                    </div>
                                </div>
                                <span class="text-gray-400 text-sm">{{ $activity->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <p class="text-gray-300">{{ $activity->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Documents -->
        @if($project->documents->count() > 0)
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8 mb-8">
                <h3 class="text-2xl font-bold text-white mb-6">Project Documents</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($project->documents as $document)
                        <div class="bg-white/5 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <i class="fas fa-file text-purple-400 text-lg"></i>
                                <a href="{{ route('admin.custom-project-documents.download', $document) }}" class="text-blue-400 hover:text-blue-300 transition-colors">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                            <h4 class="text-white font-medium truncate">{{ $document->name }}</h4>
                            <div class="text-sm text-gray-400 mt-1">
                                <div>{{ $document->mime_type }}</div>
                                <div>{{ $document->size ? number_format($document->size / 1024, 1) . ' KB' : 'Unknown size' }}</div>
                                <div>{{ $document->created_at->format('M d, Y') }}</div>
                                <div>by {{ $document->uploadedBy->name ?? 'Unknown' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Time Logs -->
        @if($project->timeLogs->count() > 0)
            <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Time Logs</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-white">
                        <thead>
                            <tr class="border-b border-gray-700/30">
                                <th class="text-left py-3 px-2">Developer</th>
                                <th class="text-left py-3 px-2">Date</th>
                                <th class="text-left py-3 px-2">Hours</th>
                                <th class="text-left py-3 px-2">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->timeLogs as $timeLog)
                                <tr class="border-b border-gray-700/20">
                                    <td class="py-3 px-2">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                {{ substr($timeLog->user->name, 0, 1) }}
                                            </div>
                                            <span>{{ $timeLog->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2">{{ $timeLog->date->format('M d, Y') }}</td>
                                    <td class="py-3 px-2">
                                        <span class="font-bold text-green-400">{{ $timeLog->hours_spent }}</span>
                                    </td>
                                    <td class="py-3 px-2">{{ $timeLog->description }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
