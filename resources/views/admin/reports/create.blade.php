@extends('layouts.admin')

@section('page-title', 'Create Report')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('admin.reports.index') }}" class="mr-4 p-2 bg-white/10 rounded-lg hover:bg-white/20 transition-colors">
                    <i class="fas fa-arrow-left text-white"></i>
                </a>
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">Create Report</h1>
                    <p class="text-gray-300">Generate a new analytics report</p>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto">
            <form action="{{ route('admin.reports.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Report Information -->
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                    <div class="px-8 py-6 border-b border-gray-700/30">
                        <h3 class="text-2xl font-bold text-white">Report Information</h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Report Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('name')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-300 mb-2">Report Type</label>
                            <select id="type" name="type" required
                                class="w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select a report type</option>
                                <option value="project_status" {{ old('type') == 'project_status' ? 'selected' : '' }}>Project Status Report</option>
                                <option value="team_performance" {{ old('type') == 'team_performance' ? 'selected' : '' }}>Team Performance Report</option>
                                <option value="time_tracking" {{ old('type') == 'time_tracking' ? 'selected' : '' }}>Time Tracking Report</option>
                                <option value="budget_analysis" {{ old('type') == 'budget_analysis' ? 'selected' : '' }}>Budget Analysis Report</option>
                                <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>Custom Report</option>
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Report Filters -->
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                    <div class="px-8 py-6 border-b border-gray-700/30">
                        <h3 class="text-2xl font-bold text-white">Report Filters</h3>
                        <p class="text-gray-400 mt-2">Configure filters to customize your report data</p>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-300 mb-2">From Date</label>
                                <input type="date" id="date_from" name="filters[date_from]" value="{{ old('filters.date_from') }}"
                                    class="w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-300 mb-2">To Date</label>
                                <input type="date" id="date_to" name="filters[date_to]" value="{{ old('filters.date_to') }}"
                                    class="w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label for="team_id" class="block text-sm font-medium text-gray-300 mb-2">Team Filter</label>
                            <select id="team_id" name="filters[team_id]"
                                class="w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Teams</option>
                                <!-- Teams would be populated here -->
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status Filter</label>
                            <select id="status" name="filters[status]"
                                class="w-full px-4 py-3 bg-white/10 border border-gray-600 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Statuses</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="on_hold">On Hold</option>
                                <option value="pending">Pending</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Report Preview -->
                <div class="bg-white/5 backdrop-blur-sm border border-gray-700/30 rounded-2xl shadow-xl">
                    <div class="px-8 py-6 border-b border-gray-700/30">
                        <h3 class="text-2xl font-bold text-white">Report Preview</h3>
                        <p class="text-gray-400 mt-2">Preview of what your report will include</p>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-6 bg-white/5 rounded-xl">
                                <div class="text-3xl font-bold text-white mb-2">📊</div>
                                <div class="text-gray-300">Analytics Data</div>
                            </div>
                            <div class="text-center p-6 bg-white/5 rounded-xl">
                                <div class="text-3xl font-bold text-white mb-2">📈</div>
                                <div class="text-gray-300">Performance Metrics</div>
                            </div>
                            <div class="text-center p-6 bg-white/5 rounded-xl">
                                <div class="text-3xl font-bold text-white mb-2">📋</div>
                                <div class="text-gray-300">Detailed Breakdown</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.reports.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-xl hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transform hover:scale-105 transition-all duration-200 shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Create Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
