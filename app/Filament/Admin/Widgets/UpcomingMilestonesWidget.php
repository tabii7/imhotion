<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class UpcomingMilestonesWidget extends Widget
{
    protected static string $view = 'filament.widgets.upcoming-milestones-widget';

    public array $milestones = [];

    public function mount(): void
    {
        try {
            if (class_exists(\App\Models\Project::class)) {
                $this->milestones = \App\Models\Project::query()
                    ->whereBetween('due_date', [now(), now()->addDays(7)])
                    ->orderBy('due_date')
                    ->limit(8)
                    ->get(['id','title','due_date'])
                    ->map(fn($p) => ['id'=>$p->id,'title'=>$p->title,'due_date'=>$p->due_date ? $p->due_date->format('Y-m-d') : null])
                    ->toArray();
            }
        } catch (\Throwable $e) {
            $this->milestones = [];
        }
    }
}
