<div class="p-4 bg-gray-900/40 border border-gray-800 rounded-lg shadow-sm filament-widget-card">
  <div class="flex items-center justify-between mb-3">
    <div class="text-sm text-gray-300">Server load</div>
    <div class="text-xs text-gray-400">{{ $stats['host'] ?? php_uname('n') }}</div>
  </div>
  <div class="grid grid-cols-2 gap-3 text-sm text-gray-300">
    <div class="text-xs">Load (1m)</div><div class="font-semibold text-white">{{ number_format($stats['load_1'] ?? 0, 2) }}</div>
    <div class="text-xs">Load (5m)</div><div class="font-semibold text-white">{{ number_format($stats['load_5'] ?? 0, 2) }}</div>
    <div class="text-xs">Load (15m)</div><div class="font-semibold text-white">{{ number_format($stats['load_15'] ?? 0, 2) }}</div>
    <div class="text-xs">Memory used</div><div class="font-semibold text-white">{{ isset($stats['memory_usage']) ? round($stats['memory_usage']/1024/1024,2) . ' MB' : '-' }}</div>
    <div class="text-xs">Memory peak</div><div class="font-semibold text-white">{{ isset($stats['memory_peak']) ? round($stats['memory_peak']/1024/1024,2) . ' MB' : '-' }}</div>
    <div class="text-xs">Uptime</div><div class="text-xs text-gray-400">{{ $stats['uptime'] ?? '' }}</div>
  </div>
</div>
