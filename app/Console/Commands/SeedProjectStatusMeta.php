<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use Carbon\Carbon;

class SeedProjectStatusMeta extends Command
{
    protected $signature = 'imhotion:seed-status-meta';
    protected $description = 'Fill missing pending/cancelled notes, due dates, and completed dates for existing projects';

    public function handle(): int
    {
        $pendingSamples  = ['Waiting info', 'Due payment', 'Awaiting approval'];
        $cancelSamples   = ['Client cancelled', 'Budget constraints', 'Scope change'];

        $now = Carbon::now();

        $updated = 0;
        Project::chunkById(100, function ($chunk) use (&$updated, $pendingSamples, $cancelSamples, $now) {
            foreach ($chunk as $p) {
                switch ($p->status) {
                    case 'pending':
                        if (empty($p->pending_note)) {
                            $p->pending_note = $pendingSamples[array_rand($pendingSamples)];
                        }
                        break;

                    case 'in_progress':
                        if (empty($p->due_date)) {
                            $p->due_date = $now->copy()->addDays(rand(3, 21))->format('Y-m-d');
                        }
                        break;

                    case 'completed':
                        if (empty($p->completed_at)) {
                            $p->completed_at = $now->copy()->subDays(rand(1, 60))->format('Y-m-d');
                        }
                        break;

                    case 'cancelled':
                        if (empty($p->cancel_reason)) {
                            $p->cancel_reason = $cancelSamples[array_rand($cancelSamples)];
                        }
                        break;
                }

                if ($p->isDirty()) {
                    $p->save();
                    $updated++;
                }
            }
        });

        $this->info("Updated {$updated} project(s).");
        return self::SUCCESS;
    }
}
