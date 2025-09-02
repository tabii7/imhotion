<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class KpisWidget extends Widget
{
    protected static string $view = 'filament.widgets.kpis-widget';

    public array $kpis = [];

    public function mount(): void
    {
        // Safe queries: check model/table existence to avoid fatal errors
        $totalRevenue = 0;
        $newClients = 0;
        $activeProjects = 0;
        $openInvoices = 0;

        try {
            if (class_exists(\App\Models\Invoice::class)) {
                $totalRevenue = (float) \App\Models\Invoice::query()->whereYear('created_at', now()->year)->sum('total');
                $openInvoices = (int) \App\Models\Invoice::query()->where('status', 'unpaid')->count();
            }
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            if (class_exists(\App\Models\User::class)) {
                $newClients = (int) \App\Models\User::query()->where('created_at', '>=', now()->subDays(30))->count();
            }
        } catch (\Throwable $e) {}

        try {
            if (class_exists(\App\Models\Project::class)) {
                $activeProjects = (int) \App\Models\Project::query()->where('status', 'in_progress')->count();
            }
        } catch (\Throwable $e) {}

        $this->kpis = [
            'total_revenue' => $totalRevenue,
            'new_clients' => $newClients,
            'active_projects' => $activeProjects,
            'open_invoices' => $openInvoices,
        ];
    }
}
