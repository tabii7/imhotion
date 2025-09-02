<div class="p-4 bg-gray-900/40 border border-gray-800 rounded-lg shadow-sm filament-widget-card">
  <div class="text-sm text-gray-300 mb-3">Projects pipeline</div>
  <ul class="space-y-2 text-sm">
    @foreach($counts as $status => $count)
      <li class="flex items-center justify-between py-1">
        <div class="flex items-center gap-3">
          <span class="w-3 h-3 rounded-full" style="background:{{ $colors[$status] ?? '#888' }}"></span>
          <span class="capitalize text-gray-200">{{ str_replace('_',' ', $status) }}</span>
        </div>
        <span class="font-semibold text-white">{{ $count }}</span>
      </li>
    @endforeach
  </ul>
</div>
