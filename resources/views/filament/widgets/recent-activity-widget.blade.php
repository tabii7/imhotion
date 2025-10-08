<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-clock class="h-5 w-5" />
                Recent Activity
            </div>
        </x-slot>
        
        <x-slot name="description">
            Latest system activities and updates
        </x-slot>

        <div class="flow-root">
            <ul role="list" class="-mb-8">
                @forelse($activities as $index => $activity)
                    <li>
                        <div class="relative pb-8">
                            @if($index < count($activities) - 1)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                            @endif
                            
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-900 {{ $this->getActivityColor($activity['color']) }}">
                                        @if($activity['icon'] === 'heroicon-o-folder')
                                            <x-heroicon-o-folder class="h-4 w-4" />
                                        @elseif($activity['icon'] === 'heroicon-o-user-plus')
                                            <x-heroicon-o-user-plus class="h-4 w-4" />
                                        @elseif($activity['icon'] === 'heroicon-o-currency-euro')
                                            <x-heroicon-o-currency-euro class="h-4 w-4" />
                                        @elseif($activity['icon'] === 'heroicon-o-user-group')
                                            <x-heroicon-o-user-group class="h-4 w-4" />
                                        @else
                                            <x-heroicon-o-bell class="h-4 w-4" />
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-900 dark:text-white font-medium">
                                            {{ $activity['title'] }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $activity['description'] }}
                                        </p>
                                    </div>
                                    
                                    <div class="whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                        <time datetime="{{ $activity['time']->toISOString() }}">
                                            {{ $activity['time']->diffForHumans() }}
                                        </time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li>
                        <div class="text-center py-8">
                            <x-heroicon-o-clock class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                            <p class="text-gray-500 dark:text-gray-400">No recent activity</p>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
        
        @if(count($activities) > 0)
            <div class="mt-6">
                <a href="{{ route('filament.admin.resources.projects.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    View All Activities
                    <x-heroicon-o-arrow-right class="ml-2 h-4 w-4" />
                </a>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>

