<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ProjectStatusWidget extends ChartWidget
{
    protected static ?string $heading = 'Project Status Distribution';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $statuses = Project::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = array_keys($statuses);
        $data = array_values($statuses);

        return [
            'datasets' => [
                [
                    'label' => 'Projects',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',   // Green for completed
                        'rgb(251, 191, 36)',  // Yellow for in_progress
                        'rgb(239, 68, 68)',   // Red for pending
                        'rgb(99, 102, 241)',  // Blue for editing
                        'rgb(156, 163, 175)', // Gray for cancelled
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                    ]
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) { return context.label + ": " + context.parsed + " projects"; }'
                    ]
                ]
            ]
        ];
    }
}
