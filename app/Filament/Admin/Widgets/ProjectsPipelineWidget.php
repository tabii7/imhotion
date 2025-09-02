<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class ProjectsPipelineWidget extends Widget
{
    protected static string $view = 'filament.widgets.projects-pipeline-widget';

    public array $counts = [];

    public function mount(): void
    {
        $statuses = ['pending','in_progress','editing','completed','finalized','cancelled'];
        foreach ($statuses as $s) {
            try {
                if (class_exists(\App\Models\Project::class)) {
                    $this->counts[$s] = \App\Models\Project::query()->where('status', $s)->count();
                } else {
                    $this->counts[$s] = 0;
                }
            } catch (\Throwable $e) {
                $this->counts[$s] = 0;
            }
        }
    }
}
