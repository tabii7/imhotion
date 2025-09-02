<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class RevenueTrendWidget extends Widget
{
    protected static string $view = 'filament.widgets.revenue-trend-widget';

    public array $labels = [];
    public array $values = [];

    public function mount(): void
    {
        $days = 30;
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $this->labels[] = $date->format('d M');
            try {
                if (class_exists(\App\Models\Invoice::class)) {
                    $this->values[] = (float) \App\Models\Invoice::query()->whereDate('created_at', $date)->sum('total');
                } else {
                    $this->values[] = 0;
                }
            } catch (\Throwable $e) {
                $this->values[] = 0;
            }
        }
    }
}
