<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:temp {--days=2 : Remove files older than this many days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove temporary ZIP files from storage/app/temp older than N days';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $path = storage_path('app/temp');

        if (!is_dir($path)) {
            $this->info('Temp directory not found: ' . $path);
            return 0;
        }

        $files = glob($path . '/*');
        $now = time();
        $count = 0;

        foreach ($files as $file) {
            if (!is_file($file)) continue;
            $mtime = filemtime($file);
            if ($mtime === false) continue;
            if ($now - $mtime > ($days * 86400)) {
                @unlink($file);
                $count++;
            }
        }

        $this->info("Removed $count files older than $days days from temp directory.");
        return 0;
    }
}
