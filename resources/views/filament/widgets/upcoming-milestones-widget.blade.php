<div class="p-4 bg-gray-900/40 border border-gray-800 rounded-lg shadow-sm filament-widget-card">
  <div class="text-sm text-gray-300 mb-3">Upcoming milestones (7 days)</div>
  <div class="space-y-2">
    @forelse($milestones as $m)
      <div class="flex items-center justify-between py-2 border-b border-gray-800">
        <div class="text-sm text-gray-200">{{ $m['title'] }}</div>
        <div class="text-xs text-gray-400">{{ $m['due_date'] }}</div>
      </div>
    @empty
      <div class="py-2 text-gray-400">No milestones</div>
    @endforelse
  </div>
</div>
