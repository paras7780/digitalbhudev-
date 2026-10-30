<?php

namespace App\Services;

use App\Models\PanchangEntry;
use App\Models\Rashifal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AstroApiService
{
    protected VedicAstroEngine $engine;

    public function __construct(VedicAstroEngine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Fetch or calculate Panchang for a specific date.
     */
    public function getPanchang(Carbon $date, string $location = VedicAstroEngine::DEFAULT_LOCATION): array
    {
        $apiKey = config('services.astro.key');

        if (!empty($apiKey)) {
            try {
                $apiResult = $this->queryExternalPanchangApi($date, $location, $apiKey);
                if (!empty($apiResult)) {
                    return $apiResult;
                }
            } catch (Throwable $e) {
                Log::warning('External Panchang API query failed, falling back to VedicAstroEngine', [
                    'error' => $e->getMessage(),
                    'date'  => $date->toDateString(),
                ]);
            }
        }

        // Reliable fallback calculation engine
        return $this->engine->computePanchang($date, $location);
    }

    /**
     * Fetch or calculate Rashifal for all 12 signs for a specific date.
     */
    public function getRashifal(Carbon $date): array
    {
        $apiKey = config('services.astro.key');

        if (!empty($apiKey)) {
            try {
                $apiResult = $this->queryExternalRashifalApi($date, $apiKey);
                if (!empty($apiResult)) {
                    return $apiResult;
                }
            } catch (Throwable $e) {
                Log::warning('External Rashifal API query failed, falling back to VedicAstroEngine', [
                    'error' => $e->getMessage(),
                    'date'  => $date->toDateString(),
                ]);
            }
        }

        // Reliable fallback calculation engine
        return $this->engine->computeRashifal($date);
    }

    /**
     * Synchronize and pre-cache a range of upcoming days into MySQL.
     * Prevents empty pages even if API or internet is unavailable in the future.
     */
    public function syncDays(Carbon $startDate, int $days = 7, string $location = VedicAstroEngine::DEFAULT_LOCATION, bool $force = false): array
    {
        $panchangCount = 0;
        $rashifalCount = 0;

        for ($i = 0; $i < $days; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $dateString = $currentDate->toDateString();

            // 1. Sync Panchang
            $existingPanchang = PanchangEntry::whereDate('date', $dateString)
                ->where(function ($q) use ($location) {
                    $q->where('location', $location)
                      ->orWhereNull('location');
                })->first();

            if (!$existingPanchang || $force) {
                $panchangData = $this->getPanchang($currentDate, $location);
                PanchangEntry::updateOrCreate(
                    ['date' => $dateString, 'location' => $location],
                    $panchangData
                );
                $panchangCount++;
            }

            // 2. Sync Rashifal for all 12 signs
            $existingRashifalCount = Rashifal::whereDate('date', $dateString)->count();
            if ($existingRashifalCount < 12 || $force) {
                $rashifalData = $this->getRashifal($currentDate);
                foreach ($rashifalData as $sign => $rData) {
                    Rashifal::updateOrCreate(
                        ['sign' => $sign, 'date' => $dateString],
                        $rData
                    );
                    $rashifalCount++;
                }
            }
        }

        return [
            'days_requested'  => $days,
            'start_date'      => $startDate->toDateString(),
            'end_date'        => $startDate->copy()->addDays($days - 1)->toDateString(),
            'panchang_synced' => $panchangCount,
            'rashifal_synced' => $rashifalCount,
        ];
    }

    /**
     * Query an external provider if credentials are configured in .env.
     */
    protected function queryExternalPanchangApi(Carbon $date, string $location, string $apiKey): ?array
    {
        $provider = config('services.astro.provider', 'prokerala');

        if ($provider === 'prokerala') {
            $clientId = config('services.astro.client_id');
            if (empty($clientId)) {
                return null;
            }

            // Prokerala OAuth token & Panchang request
            $tokenRes = Http::asForm()->timeout(5)->post('https://api.prokerala.com/token', [
                'grant_type'    => 'client_credentials',
                'client_id'     => $clientId,
                'client_secret' => $apiKey,
            ]);

            if ($tokenRes->successful()) {
                $token = $tokenRes->json('access_token');
                $res = Http::withToken($token)->timeout(8)->get('https://api.prokerala.com/v2/astrology/panchang', [
                    'ayanamsa'    => 1, // Lahiri
                    'coordinates' => VedicAstroEngine::DEFAULT_LAT . ',' . VedicAstroEngine::DEFAULT_LON,
                    'datetime'    => $date->toIso8601String(),
                ]);

                if ($res->successful()) {
                    $d = $res->json('data');
                    return [
                        'date'         => $date->toDateString(),
                        'location'     => $location,
                        'timezone'     => VedicAstroEngine::DEFAULT_TZ,
                        'vaar'         => $d['vaara'] ?? $date->format('l'),
                        'tithi'        => ($d['tithi'][0]['name'] ?? 'Panchami') . ' (' . ($d['tithi'][0]['paksha'] ?? '') . ')',
                        'nakshatra'    => $d['nakshatra'][0]['name'] ?? 'Rohini',
                        'yoga'         => $d['yoga'][0]['name'] ?? 'Siddhi',
                        'karana'       => $d['karana'][0]['name'] ?? 'Bava',
                        'sunrise'      => isset($d['sunrise']) ? Carbon::parse($d['sunrise'])->format('h:i A') : null,
                        'sunset'       => isset($d['sunset']) ? Carbon::parse($d['sunset'])->format('h:i A') : null,
                        'moonrise'     => isset($d['moonrise']) ? Carbon::parse($d['moonrise'])->format('h:i A') : null,
                        'moonset'      => isset($d['moonset']) ? Carbon::parse($d['moonset'])->format('h:i A') : null,
                        'notes'        => 'Data synchronized via Prokerala Astrology API.',
                        'status'       => 'published',
                        'published_at' => now(),
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Query external Rashifal provider if credentials configured.
     */
    protected function queryExternalRashifalApi(Carbon $date, string $apiKey): ?array
    {
        // Provider implementation hook for external horoscope endpoints
        return null;
    }
}
