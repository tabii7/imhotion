<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-user-group class="h-5 w-5" />
                Team Performance
            </div>
        </x-slot>
        
        <x-slot name="description">
            Team statistics and top performers
        </x-slot>

        <div class="space-y-6">
            <!-- Team Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm">Total Teams</p>
                            <p class="text-2xl font-bold">{{ $teamStats['total_teams'] }}</p>
                        </div>
                        <x-heroicon-o-user-group class="h-8 w-8 text-blue-200" />
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm">Active Teams</p>
                            <p class="text-2xl font-bold">{{ $teamStats['active_teams'] }}</p>
                        </div>
                        <x-heroicon-o-check-circle class="h-8 w-8 text-green-200" />
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm">Total Developers</p>
                            <p class="text-2xl font-bold">{{ $teamStats['total_developers'] }}</p>
                        </div>
                        <x-heroicon-o-code-bracket class="h-8 w-8 text-purple-200" />
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm">Available</p>
                            <p class="text-2xl font-bold">{{ $teamStats['available_developers'] }}</p>
                        </div>
                        <x-heroicon-o-user class="h-8 w-8 text-orange-200" />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Top Performers -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Top Performers</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($topPerformers as $index => $developer)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $developer['name'] }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $developer['specialization'] ?? 'General' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $developer['completed_projects'] }} completed
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $developer['active_projects'] }} active
                                            </p>
                                        </div>
                                        <div class="flex items-center">
                                            @if($developer['is_available'])
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    Available
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    Busy
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center">
                                <x-heroicon-o-user-group class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                                <p class="text-gray-500 dark:text-gray-400">No developers found</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Team Performance -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Team Performance</h3>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($teamPerformance as $team)
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $team['name'] }}
                                            </h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($this->getStatusColor($team['status']) === 'success') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($this->getStatusColor($team['status']) === 'danger') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                @endif">
                                                {{ ucfirst($team['status']) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            Lead: {{ $team['team_lead'] }} • {{ $team['member_count'] }} members
                                        </p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <div class="flex space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                                <span>{{ $team['total_projects'] }} total</span>
                                                <span>{{ $team['completed_projects'] }} completed</span>
                                                <span>{{ $team['active_projects'] }} active</span>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $team['completion_rate'] }}%
                                            </div>
                                        </div>
                                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $team['completion_rate'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center">
                                <x-heroicon-o-user-group class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                                <p class="text-gray-500 dark:text-gray-400">No teams found</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>


