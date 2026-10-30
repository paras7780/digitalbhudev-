<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Background Cron Tasks
|--------------------------------------------------------------------------
| Runs daily at 00:05 IST to pre-fetch and store today's and upcoming
| Panchang and 12-sign Rashifal into MySQL. If execution fails, an
| immediate email notification is dispatched to the administrator.
*/
Schedule::command('astro:sync')
    ->dailyAt('00:05')
    ->timezone('Asia/Kolkata')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/astro-sync.log'))
    ->onFailure(function () {
        $adminEmail = \App\Models\User::where('role', 'admin')->value('email') 
            ?? config('mail.from.address', 'admin@example.com');

        $logPath = storage_path('logs/astro-sync.log');
        $tailError = 'Cron process exited with non-zero exit code or uncaught exception.';
        if (file_exists($logPath)) {
            $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if (!empty($lines)) {
                $tailError = implode("\n", array_slice($lines, -8));
            }
        }

        try {
            \Illuminate\Support\Facades\Mail::to($adminEmail)
                ->send(new \App\Mail\CronFailedMail('astro:sync', $tailError));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending cron failure alert email', [
                'recipient' => $adminEmail,
                'error'     => $e->getMessage(),
            ]);
        }
    });

