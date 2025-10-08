<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Welcome to Imhotion Admin</h1>
                    <p class="text-blue-100">Manage your projects, teams, and users from this central dashboard</p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white/20 rounded-lg p-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold">{{ now()->format('H:i') }}</div>
                            <div class="text-sm text-blue-100">{{ now()->format('l, F j, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('filament.admin.resources.projects.index') }}" 
               class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <x-heroicon-o-folder class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Projects</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Manage all projects</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('filament.admin.resources.users.index') }}" 
               class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <x-heroicon-o-users class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Users</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Manage users and roles</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('filament.admin.resources.teams.index') }}" 
               class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <x-heroicon-o-user-group class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Teams</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Manage teams</p>
                    </div>
                </div>
            </a>

        </div>

        <!-- Widgets will be rendered here by Filament -->
        <div class="space-y-6">
            @foreach($this->getWidgets() as $widget)
                @livewire($widget)
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
