<div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
  @php
    // helper to render a single KPI card
    $kpiCard = fn($title, $value, $note = null, $icon = null, $iconClass = 'text-indigo-300') => (object)[
      'title' => $title,
      'value' => $value,
      'note' => $note,
      'icon' => $icon,
      'iconClass' => $iconClass,
    ];
  @endphp

  @foreach([
    $kpiCard('Total revenue (YTD)', '€'.number_format($kpis['total_revenue'] ?? 0,0,',','.'), $kpis['revenue_change'] ?? '—', 'currency-euro', 'text-yellow-400'),
    $kpiCard('New clients (30d)', $kpis['new_clients'] ?? 0, $kpis['new_clients_change'] ?? '', 'user-group', 'text-indigo-300'),
    $kpiCard('Active projects', $kpis['active_projects'] ?? 0, $kpis['active_projects_note'] ?? '', 'briefcase', 'text-blue-300'),
    $kpiCard('Open invoices', $kpis['open_invoices'] ?? 0, $kpis['open_invoices_amount'] ?? '', 'document-text', 'text-pink-300'),
  ] as $card)
    <div class="p-4 bg-gray-900/40 border border-gray-800 rounded-lg shadow-sm filament-widget-card">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-300">{{ $card->title }}</div>
        @if($card->icon)
          <x-heroicon-o-{{ $card->icon }} class="w-5 h-5 {{ $card->iconClass }}" />
        @endif
      </div>
      <div class="text-2xl font-semibold mt-3 text-white">{!! $card->value !!}</div>
      @if($card->note)
        <div class="text-xs text-gray-400 mt-1">{{ $card->note }}</div>
      @endif
    </div>
  @endforeach
</div>
