<?php

namespace App\Console\Commands;

use App\Models\BhudevInstagramSetting;
use App\Services\InstagramIntegrationService;
use Illuminate\Console\Command;

class SyncInstagramLibraries extends Command
{
    protected $signature = 'instagram:sync
                            {--profile= : Sync only this Bhudev profile id}';

    protected $description = 'Pull the latest posts and Reels from every connected Instagram account into the Bhudev content library';

    public function handle(InstagramIntegrationService $service): int
    {
        $query = BhudevInstagramSetting::with('profile')->where('connection_status', 'connected');

        if ($this->option('profile')) {
            $query->where('bhudev_profile_id', (int) $this->option('profile'));
        }

        $settings = $query->get();

        if ($settings->isEmpty()) {
            $this->info('No connected Instagram accounts to sync.');
            return self::SUCCESS;
        }

        $failures = 0;

        foreach ($settings as $setting) {
            $profile = $setting->profile;
            if (!$profile) {
                continue;
            }

            $result = $service->syncLibrary($profile);

            if ($result['success']) {
                $this->line(sprintf('✓ %s: %d imported, %d removed', $profile->display_name, $result['imported'], $result['removed']));
            } else {
                $failures++;
                $this->warn(sprintf('✕ %s: %s', $profile->display_name, $result['error']));
            }
        }

        return $failures > 0 ? self::FAILURE : self::SUCCESS;
    }
}
