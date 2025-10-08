@php
    $statusColors = [
        'pending' => 'bg-gray-100 text-gray-800',
        'in_progress' => 'bg-yellow-100 text-yellow-800',
        'completed' => 'bg-green-100 text-green-800',
        'on_hold' => 'bg-orange-100 text-orange-800',
        'finalized' => 'bg-blue-100 text-blue-800',
        'cancelled' => 'bg-red-100 text-red-800'
    ];
    
    $statusLabels = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'on_hold' => 'On Hold',
        'finalized' => 'Finalized',
        'cancelled' => 'Cancelled'
    ];

    // Determine card background based on section
    $cardBg = $section === 'finalized' ? 'bg-slate-800/50' : 'bg-sidebar-active';
    $cardBorder = $section === 'finalized' ? 'border-slate-600' : 'border-blue-300';
    $idBg = $section === 'finalized' ? 'bg-slate-600' : 'bg-blue-600';
    
    // Format delivery date
    $deliveryText = '';
    if ($project->delivery_date) {
        $deliveryDate = \Carbon\Carbon::parse($project->delivery_date);
        if ($section === 'finalized') {
            $deliveryText = 'Delivered on ' . $deliveryDate->format('j F');
        } else {
            $deliveryText = 'Delivery by ' . $deliveryDate->format('j F');
        }
    } else {
        $deliveryText = $section === 'finalized' ? 'No delivery date' : 'TBD';
    }
@endphp

<div class="{{ $cardBg }} border {{ $cardBorder }} rounded-2xl p-1.5 mb-3 flex items-center min-h-[65px] transition-all duration-200 cursor-pointer hover:scale-105 hover:bg-blue-900/30"
     data-project-id="{{ $project->id }}"
     data-project-title="{{ $project->title }}"
     data-project-topic="{{ $project->topic }}"
     data-project-status="{{ $project->status }}"
     data-project-delivery-date="{{ $project->delivery_date }}"
     data-project-days-used="{{ $project->days_used }}"
     data-project-day-budget="{{ $project->day_budget }}"
     data-project-notes="{{ $project->notes }}"
     data-project-start-date="{{ $project->start_date }}"
     data-project-end-date="{{ $project->end_date }}"
     data-project-progress="{{ $project->progress ?? 0 }}">
    
    <!-- Project Number -->
    <div class="w-12 h-8 {{ $idBg }} text-white border {{ $cardBorder }} rounded-lg flex items-center justify-center font-bold text-sm flex-shrink-0 mr-5">
        {{ $project->id }}
    </div>
    
    <!-- Project Info -->
    <div class="flex-1 min-w-0">
        <div class="text-white text-base font-semibold mb-1 leading-tight">
            {{ $project->title ?? 'Project Name' }} - {{ $project->topic ?? 'Topic' }}
        </div>
        <div class="text-sidebar-text text-sm font-normal leading-tight mb-2">
            {{ $deliveryText }}
        </div>
        
        <!-- Progress Information -->
        <div class="flex items-center space-x-4 text-xs">
            @if($project->assignedDeveloper)
                <div class="flex items-center text-blue-300">
                    <i class="fas fa-user mr-1"></i>
                    <span>{{ $project->assignedDeveloper->name }}</span>
                </div>
            @endif
            
            <div class="flex items-center text-green-300">
                <i class="fas fa-clock mr-1"></i>
                <span>{{ number_format($project->total_hours_worked, 1) }}h used</span>
            </div>
            
            @if($project->overall_progress > 0)
                <div class="flex items-center text-yellow-300">
                    <i class="fas fa-chart-line mr-1"></i>
                    <span>{{ $project->overall_progress }}% complete</span>
                </div>
            @endif
            
            @if($project->public_files->count() > 0)
                <div class="flex items-center text-purple-300">
                    <i class="fas fa-file-upload mr-1"></i>
                    <span>{{ $project->public_files->count() }} files</span>
                </div>
            @endif
        </div>
        
        <!-- Progress Bar -->
        @if($project->overall_progress > 0)
            <div class="mt-2">
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2 rounded-full transition-all duration-500" 
                         style="width: {{ $project->overall_progress }}%"></div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Status Badge -->
    <div class="mx-4 flex-shrink-0">
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-800' }}">
            {{ $statusLabels[$project->status] ?? ucfirst($project->status) }}
        </span>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex-shrink-0 flex space-x-2">
        @if($section === 'finalized')
            @if($project->status === 'cancelled')
                <button class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                    Expired
                </button>
            @else
                <a href="{{ route('progress.show', $project) }}" 
                   class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors duration-200">
                    <i class="fas fa-chart-line mr-1"></i>
                    View Progress
                </a>
                <button onclick="downloadFiles({{ $project->id }})" 
                        class="bg-brand-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors duration-200">
                    <i class="fas fa-download mr-1"></i>
                    Files
                </button>
            @endif
        @else
            <a href="{{ route('progress.show', $project) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors duration-200">
                <i class="fas fa-chart-line mr-1"></i>
                Progress
            </a>
            <button onclick="downloadFiles({{ $project->id }})" 
                    class="bg-brand-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors duration-200">
                <i class="fas fa-download mr-1"></i>
                Files
            </button>
        @endif
    </div>
</div>