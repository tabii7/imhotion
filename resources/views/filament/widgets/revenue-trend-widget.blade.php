<div class="p-4 bg-gray-900/40 border border-gray-800 rounded-lg shadow-sm filament-widget-card">
  <div class="flex items-center justify-between mb-3">
    <div class="text-sm text-gray-300">Revenue (last 30 days)</div>
    <div class="text-xs text-gray-400">€{{ number_format(array_sum($values ?? [0]),0,',','.') }} total</div>
  </div>
  <canvas id="revenueTrendChart" style="height:220px;"></canvas>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    (function(){
      const ctx = document.getElementById('revenueTrendChart').getContext('2d');
      const labels = @json($labels);
      const data = @json($values);
      const brand = getComputedStyle(document.documentElement).getPropertyValue('--brand-primary').trim() || '#0066ff';
      new Chart(ctx, {
        type: 'line',
        data: {
          labels,
          datasets: [{
            label: 'Revenue',
            data,
            borderColor: brand,
            backgroundColor: brand + '18',
            fill: true,
            tension: 0.3,
            pointRadius: 0,
          }]
        },
        options: {
          responsive:true,
          plugins:{ legend:{ display:false } },
          scales:{
            x:{ grid:{ color: 'rgba(255,255,255,0.04)' }, ticks:{ color: 'rgba(255,255,255,0.6)' } },
            y:{ grid:{ color: 'rgba(255,255,255,0.04)' }, ticks:{ color: 'rgba(255,255,255,0.6)', callback: v => '€' + v } }
          }
        }
      });
    })();
  </script>
</div>
