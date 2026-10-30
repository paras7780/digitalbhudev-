<?php

namespace App\Console\Commands;

use App\Services\AstroApiService;
use App\Services\VedicAstroEngine;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncAstroContent extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'astro:sync 
                            {--days=7 : Number of upcoming days to pre-fetch and store}
                            {--date= : Specific starting date (YYYY-MM-DD)}
                            {--location= : Target location name}
                            {--force : Force overwrite existing records}';

    /**
     * The console command description.
     */
    protected $description = 'Pre-fetch and persist upcoming Panchang and 12-sign Rashifal into MySQL database to prevent downtime';

    /**
     * Execute the console command.
     */
    public function handle(AstroApiService $service): int
    {
        $startDate = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : now('Asia/Kolkata');

        $days = (int) $this->option('days');
        if ($days < 1) $days = 1;
        if ($days > 31) $days = 31;

        $location = $this->option('location') ?: VedicAstroEngine::DEFAULT_LOCATION;
        $force = (bool) $this->option('force');

        $this->info("Starting Astro Sync for {$days} day(s) starting from {$startDate->toDateString()} ({$location})...");

        $results = $service->syncDays($startDate, $days, $location, $force);

        $this->table(
            ['Metric', 'Value'],
            [
                ['Start Date', $results['start_date']],
                ['End Date', $results['end_date']],
                ['Days Pre-cached', $results['days_requested']],
                ['Panchang Records Synced', $results['panchang_synced']],
                ['Rashifal Records Synced (12 signs)', $results['rashifal_synced']],
            ]
        );

        $this->info('✓ Panchang and Rashifal records pre-cached successfully into MySQL.');

        return self::SUCCESS;
    }
}
