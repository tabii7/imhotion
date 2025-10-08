<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-folder-open class="h-5 w-5" />
                Project Overview
            </div>
        </x-slot>
        
        <x-slot name="description">
            Current project status and recent activity
        </x-slot>

        <div class="space-y-6">
            <!-- Project Status Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach(['pending', 'in_progress', 'editing', 'completed', 'finalized', 'cancelled'] as $status)
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 capitalize">
                                    {{ str_replace('_', ' ', $status) }}
                                </p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $statusCounts[$status] ?? 0 }}
                                </p>
                            </div>
                            <div class="h-8 w-8 rounded-full flex items-center justify-center
                                @if($this->getStatusColor($status) === 'primary') bg-blue-100 text-blue-600
                                @elseif($this->getStatusColor($status) === 'success') bg-green-100 text-green-600
                                @elseif($this->getStatusColor($status) === 'warning') bg-yellow-100 text-yellow-600
                                @elseif($this->getStatusColor($status) === 'danger') bg-red-100 text-red-600
                                @elseif($this->getStatusColor($status) === 'info') bg-cyan-100 text-cyan-600
                                @else bg-gray-100 text-gray-600
                                @endif">
                                @if($status === 'pending')
                                    <x-heroicon-o-clock class="h-4 w-4" />
                                @elseif($status === 'in_progress')
                                    <x-heroicon-o-play class="h-4 w-4" />
                                @elseif($status === 'editing')
                                    <x-heroicon-o-pencil class="h-4 w-4" />
                                @elseif($status === 'completed')
                                    <x-heroicon-o-check-circle class="h-4 w-4" />
                                @elseif($status === 'finalized')
                                    <x-heroicon-o-check-badge class="h-4 w-4" />
                                @else
                                    <x-heroicon-o-x-circle class="h-4 w-4" />
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Project Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Projects</p>
                            <p class="text-2xl font-bold">{{ $projectStats['total'] }}</p>
                        </div>
                        <x-heroicon-o-folder class="h-8 w-8 text-blue-200" />
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Active Projects</p>
                            <p class="text-2xl font-bold">{{ $projectStats['active'] }}</p>
                        </div>
                        <x-heroicon-o-play class="h-8 w-8 text-green-200" />
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm">Completed</p>
                            <p class="text-2xl font-bold">{{ $projectStats['completed'] }}</p>
                        </div>
                        <x-heroicon-o-check-circle class="h-8 w-8 text-purple-200" />
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm">Completion Rate</p>
                            <p class="text-2xl font-bold">{{ $projectStats['completion_rate'] }}%</p>
                        </div>
                        <x-heroicon-o-chart-bar class="h-8 w-8 text-orange-200" />
                    </div>
                </div>
            </div>

            <!-- Recent Projects -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Projects</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentProjects as $project)
                        <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $project['name'] }}
                                        </h4>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($this->getStatusColor($project['status']) === 'primary') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($this->getStatusColor($project['status']) === 'success') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($this->getStatusColor($project['status']) === 'warning') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($this->getStatusColor($project['status']) === 'danger') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @elseif($this->getStatusColor($project['status']) === 'info') bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $project['status'])) }}
                                        </span>
                                        @if($project['priority'] !== 'normal')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($this->getPriorityColor($project['priority']) === 'danger') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @elseif($this->getPriorityColor($project['priority']) === 'warning') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($this->getPriorityColor($project['priority']) === 'success') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @endif">
                                                {{ ucfirst($project['priority']) }} Priority
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        <span>Client: {{ $project['client'] }}</span>
                                        <span class="mx-2">•</span>
                                        <span>Developer: {{ $project['developer'] }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $project['created_at'] }}</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('filament.admin.resources.projects.view', $project['id']) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <x-heroicon-o-folder-open class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                            <p class="text-gray-500 dark:text-gray-400">No projects found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>


