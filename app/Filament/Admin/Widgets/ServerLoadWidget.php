<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class ServerLoadWidget extends Widget
{
    protected static string $view = 'filament.widgets.server-load-widget';

    public array $stats = [];

    public function mount(): void
    {
        $load = function_exists('sys_getloadavg') ? sys_getloadavg() : [0,0,0];
        $this->stats = [
            'load_1' => $load[0] ?? 0,
            'load_5' => $load[1] ?? 0,
            'load_15'=> $load[2] ?? 0,
            'memory_usage' => function_exists('memory_get_usage') ? memory_get_usage(true) : 0,
            'memory_peak' => function_exists('memory_get_peak_usage') ? memory_get_peak_usage(true) : 0,
            'uptime' => trim(@shell_exec('uptime 2>/dev/null') ?? ''),
        ];
    }
}
