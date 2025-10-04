@extends('layouts.admin')

@section('page-title', 'Reports & Analytics')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Reports & Analytics</h1>
                    <p class="text-gray-300">Generate and manage project reports</p>
                </div>
                <a href="{{ route('admin.reports.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Create Report
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 backdrop-blur-sm border border-blue-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $reports->total() ?? 0 }}</div>
                    <div class="text-blue-300 font-medium">Total Reports</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-600/20 backdrop-blur-sm border border-green-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $reports->where('generated_at', '!=', null)->count() }}</div>
                    <div class="text-green-300 font-medium">Generated Reports</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-purple-500/20 to-pink-600/20 backdrop-blur-sm border border-purple-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $reports->where('type', 'project_status')->count() }}</div>
                    <div class="text-purple-300 font-medium">Project Status Reports</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-yellow-500/20 to-amber-600/20 backdrop-blur-sm border border-yellow-500/30 rounded-2xl shadow-xl">
                <div class="p-6">
                    <div class="text-3xl font-bold text-white mb-2">{{ $reports->where('type', 'team_performance')->count() }}</div>
                    <div class="text-yellow-300 font-medium">Team Performance Reports</div>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
            <div class="px-8 py-6 border-b border-gray-700/30">
                <h3 class="text-2xl font-bold text-white">All Reports</h3>
            </div>
            <div class="divide-y divide-gray-700/30">
                @forelse($reports as $report)
                    <div class="p-8 hover:bg-white/5 transition-all duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-lg font-semibold text-white truncate">{{ $report->name }}</h4>
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($report->type === 'project_status') bg-gradient-to-r from-blue-500 to-cyan-500 text-white
                                            @elseif($report->type === 'team_performance') bg-gradient-to-r from-green-500 to-emerald-500 text-white
                                            @elseif($report->type === 'time_tracking') bg-gradient-to-r from-purple-500 to-pink-500 text-white
                                            @elseif($report->type === 'budget_analysis') bg-gradient-to-r from-yellow-500 to-amber-500 text-white
                                            @else bg-gradient-to-r from-gray-500 to-gray-600 text-white @endif shadow-lg">
                                            {{ ucfirst(str_replace('_', ' ', $report->type)) }}
                                        </span>
                                        @if($report->isGenerated())
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg">
                                                <i class="fas fa-check mr-1"></i>
                                                Generated
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-yellow-500 to-amber-500 text-white shadow-lg">
                                                <i class="fas fa-clock mr-1"></i>
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($report->description)
                                    <p class="text-gray-300 mb-4">{{ $report->description }}</p>
                                @endif
                                
                                <div class="flex items-center text-sm text-gray-400 mb-4">
                                    <span class="flex items-center mr-6">
                                        <i class="fas fa-user mr-2"></i>
                                        Created by {{ $report->creator->name }}
                                    </span>
                                    <span class="flex items-center mr-6">
                                        <i class="fas fa-calendar mr-2"></i>
                                        {{ $report->created_at->diffForHumans() }}
                                    </span>
                                    @if($report->generated_at)
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-2"></i>
                                            Generated {{ $report->generated_at->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="ml-6 flex-shrink-0 flex space-x-3">
                                <a href="{{ route('admin.reports.show', $report) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-700 to-gray-600 text-white rounded-xl hover:from-gray-600 hover:to-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                    <i class="fas fa-eye mr-2"></i>
                                    View
                                </a>
                                @if($report->isGenerated())
                                    <a href="{{ route('admin.reports.export', $report) }}?format=pdf" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                        <i class="fas fa-download mr-2"></i>
                                        Export
                                    </a>
                                @else
                                    <form action="{{ route('admin.reports.generate', $report) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                            <i class="fas fa-play mr-2"></i>
                                            Generate
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.reports.destroy', $report) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this report?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl hover:from-red-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-105 transition-all duration-200 shadow-lg">
                                        <i class="fas fa-trash mr-2"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-chart-bar text-white text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-3">No reports found</h3>
                        <p class="text-gray-400 text-lg mb-6">Create your first report to get started.</p>
                        <a href="{{ route('admin.reports.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Create Report
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($reports->hasPages())
            <div class="mt-8">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
