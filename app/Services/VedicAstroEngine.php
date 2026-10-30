<?php

namespace App\Services;

use Carbon\Carbon;

class VedicAstroEngine
{
    // Default reference coordinates: Ahmedabad, Gujarat (Lat: 23.0225, Long: 72.5714)
    public const DEFAULT_LAT = 23.0225;
    public const DEFAULT_LON = 72.5714;
    public const DEFAULT_LOCATION = 'Ahmedabad, Gujarat';
    public const DEFAULT_TZ = 'Asia/Kolkata';

    private const NAKSHATRAS = [
        'Ashwini', 'Bharani', 'Krittika', 'Rohini', 'Mrigashira', 'Ardra',
        'Punarvasu', 'Pushya', 'Ashlesha', 'Magha', 'Purva Phalguni', 'Uttara Phalguni',
        'Hasta', 'Chitra', 'Swati', 'Vishakha', 'Anuradha', 'Jyeshtha',
        'Mula', 'Purva Ashadha', 'Uttara Ashadha', 'Shravana', 'Dhanishta', 'Shatabhisha',
        'Purva Bhadrapada', 'Uttara Bhadrapada', 'Revati'
    ];

    private const YOGAS = [
        'Vishkambha', 'Priti', 'Ayushman', 'Saubhagya', 'Shobhana', 'Atiganda',
        'Sukarma', 'Dhriti', 'Shula', 'Ganda', 'Vriddhi', 'Dhruva',
        'Vyaghata', 'Harshana', 'Vajra', 'Siddhi', 'Vyatipata', 'Variyana',
        'Parigha', 'Shiva', 'Siddha', 'Sadhya', 'Shubha', 'Shukla',
        'Brahma', 'Indra', 'Vaidhriti'
    ];

    private const KARANAS = [
        'Bava', 'Balava', 'Kaulava', 'Taitila', 'Gara', 'Vanija', 'Vishti (Bhadra)',
        'Shakuni', 'Chatushpada', 'Naga', 'Kintughna'
    ];

    private const TITHI_NAMES = [
        1 => 'Pratipada', 2 => 'Dwitiya', 3 => 'Tritiya', 4 => 'Chaturthi',
        5 => 'Panchami', 6 => 'Shashthi', 7 => 'Saptami', 8 => 'Ashtami',
        9 => 'Navami', 10 => 'Dashami', 11 => 'Ekadashi', 12 => 'Dwadashi',
        13 => 'Trayodashi', 14 => 'Chaturdashi', 15 => 'Purnima / Amavasya'
    ];

    private const VAAR_NAMES = [
        0 => 'Ravivaar (Sunday)',
        1 => 'Somvaar (Monday)',
        2 => 'Mangalvaar (Tuesday)',
        3 => 'Budhvaar (Wednesday)',
        4 => 'Guruvaar (Thursday)',
        5 => 'Shukravaar (Friday)',
        6 => 'Shanivaar (Saturday)',
    ];

    private const ZODIAC_SIGNS = [
        'Aries' => ['hindi' => 'Mesh (मेष)', 'element' => 'Fire', 'ruler' => 'Mars', 'colors' => ['Saffron', 'Red', 'Coral'], 'numbers' => [9, 18, 27]],
        'Taurus' => ['hindi' => 'Vrishabh (वृषभ)', 'element' => 'Earth', 'ruler' => 'Venus', 'colors' => ['White', 'Light Blue', 'Cream'], 'numbers' => [6, 15, 24]],
        'Gemini' => ['hindi' => 'Mithun (मिथुन)', 'element' => 'Air', 'ruler' => 'Mercury', 'colors' => ['Green', 'Emerald', 'Yellow'], 'numbers' => [5, 14, 23]],
        'Cancer' => ['hindi' => 'Kark (कर्क)', 'element' => 'Water', 'ruler' => 'Moon', 'colors' => ['Silver', 'Pearl White', 'Sea Green'], 'numbers' => [2, 11, 20]],
        'Leo' => ['hindi' => 'Simha (सिंह)', 'element' => 'Fire', 'ruler' => 'Sun', 'colors' => ['Gold', 'Orange', 'Golden Yellow'], 'numbers' => [1, 10, 19]],
        'Virgo' => ['hindi' => 'Kanya (कन्या)', 'element' => 'Earth', 'ruler' => 'Mercury', 'colors' => ['Olive Green', 'Navy Blue', 'Earthy Brown'], 'numbers' => [5, 14, 23]],
        'Libra' => ['hindi' => 'Tula (तुला)', 'element' => 'Air', 'ruler' => 'Venus', 'colors' => ['Pastel Blue', 'Pink', 'Turquoise'], 'numbers' => [6, 15, 24]],
        'Scorpio' => ['hindi' => 'Vrishchik (वृश्चिक)', 'element' => 'Water', 'ruler' => 'Mars', 'colors' => ['Deep Red', 'Maroon', 'Rust'], 'numbers' => [9, 18, 27]],
        'Sagittarius' => ['hindi' => 'Dhanu (धनु)', 'element' => 'Fire', 'ruler' => 'Jupiter', 'colors' => ['Yellow', 'Royal Blue', 'Purple'], 'numbers' => [3, 12, 21]],
        'Capricorn' => ['hindi' => 'Makar (मकर)', 'element' => 'Earth', 'ruler' => 'Saturn', 'colors' => ['Dark Gray', 'Charcoal', 'Brown'], 'numbers' => [8, 17, 26]],
        'Aquarius' => ['hindi' => 'Kumbh (कुंभ)', 'element' => 'Air', 'ruler' => 'Saturn / Uranus', 'colors' => ['Electric Blue', 'Cyan', 'Violet'], 'numbers' => [4, 13, 22]],
        'Pisces' => ['hindi' => 'Meen (मीन)', 'element' => 'Water', 'ruler' => 'Jupiter', 'colors' => ['Aquamarine', 'Sea Green', 'Pale Yellow'], 'numbers' => [7, 16, 25]],
    ];

    /**
     * Compute full Panchang for a given date and location.
     */
    public function computePanchang(Carbon $date, string $location = self::DEFAULT_LOCATION, float $lat = self::DEFAULT_LAT, float $lon = self::DEFAULT_LON): array
    {
        $dayOfWeek = $date->dayOfWeek;
        $vaar = self::VAAR_NAMES[$dayOfWeek];

        // Julian Day calculation
        $year = $date->year;
        $month = $date->month;
        $day = $date->day;
        if ($month <= 2) {
            $year -= 1;
            $month += 12;
        }
        $a = floor($year / 100);
        $b = 2 - $a + floor($a / 4);
        $jd = floor(365.25 * ($year + 4716)) + floor(30.6001 * ($month + 1)) + $day + $b - 1524.5;

        // Days since J2000.0
        $d = $jd - 2451545.0;

        // Mean Solar Longitude (L_sun) and Mean Anomaly (M_sun)
        $L_sun = fmod(280.460 + 0.9856474 * $d, 360.0);
        if ($L_sun < 0) $L_sun += 360.0;
        $M_sun = fmod(357.528 + 0.9856003 * $d, 360.0);
        if ($M_sun < 0) $M_sun += 360.0;

        // Sun true ecliptic longitude
        $lambda_sun = $L_sun + 1.915 * sin(deg2rad($M_sun)) + 0.020 * sin(deg2rad(2 * $M_sun));
        $lambda_sun = fmod($lambda_sun, 360.0);
        if ($lambda_sun < 0) $lambda_sun += 360.0;

        // Mean Lunar Longitude (L_moon)
        $L_moon = fmod(218.316 + 13.176396 * $d, 360.0);
        if ($L_moon < 0) $L_moon += 360.0;
        $M_moon = fmod(134.963 + 13.064993 * $d, 360.0);
        if ($M_moon < 0) $M_moon += 360.0;

        // Moon true longitude with major perturbations
        $lambda_moon = $L_moon + 6.289 * sin(deg2rad($M_moon));
        $lambda_moon = fmod($lambda_moon, 360.0);
        if ($lambda_moon < 0) $lambda_moon += 360.0;

        // Lahiri Ayanamsa approximation
        $ayanamsa = 23.85 + (0.01397 * ($date->year - 2000));
        $sidereal_sun = fmod($lambda_sun - $ayanamsa + 360.0, 360.0);
        $sidereal_moon = fmod($lambda_moon - $ayanamsa + 360.0, 360.0);

        // 1. Tithi: Angular distance between Moon and Sun (each tithi = 12 deg)
        $diff = fmod($lambda_moon - $lambda_sun + 360.0, 360.0);
        $tithi_num = (int) floor($diff / 12.0) + 1; // 1 to 30
        if ($tithi_num <= 15) {
            $paksha = 'Shukla Paksha';
            $tithi_name = self::TITHI_NAMES[$tithi_num] ?? 'Purnima';
            if ($tithi_num === 15) $tithi_name = 'Purnima';
        } else {
            $paksha = 'Krishna Paksha';
            $k_num = $tithi_num - 15;
            $tithi_name = self::TITHI_NAMES[$k_num] ?? 'Amavasya';
            if ($k_num === 15) $tithi_name = 'Amavasya';
        }
        $full_tithi = "{$paksha} {$tithi_name}";

        // 2. Nakshatra: Moon's sidereal longitude divided into 27 segments of 13°20'
        $nakshatra_idx = (int) floor($sidereal_moon / (360.0 / 27.0)) % 27;
        $nakshatra = self::NAKSHATRAS[$nakshatra_idx] ?? 'Ashwini';

        // 3. Yoga: (Sidereal Sun + Sidereal Moon) divided into 27 segments
        $yoga_sum = fmod($sidereal_sun + $sidereal_moon, 360.0);
        $yoga_idx = (int) floor($yoga_sum / (360.0 / 27.0)) % 27;
        $yoga = self::YOGAS[$yoga_idx] ?? 'Siddhi';

        // 4. Karana: Half of Tithi (each 6 deg)
        $karana_step = (int) floor($diff / 6.0); // 0 to 59
        if ($karana_step === 0) {
            $karana = 'Kintughna';
        } elseif ($karana_step >= 57) {
            $fixed_k = [57 => 'Shakuni', 58 => 'Chatushpada', 59 => 'Naga'];
            $karana = $fixed_k[$karana_step] ?? 'Shakuni';
        } else {
            $karana = self::KARANAS[($karana_step - 1) % 7];
        }

        // 5. Sun Rise & Sun Set calculation
        $sunTimes = $this->calculateSunTimes($date, $lat, $lon);

        // 6. Moon Rise & Moon Set calculation
        $moonTimes = $this->calculateMoonTimes($date, $sunTimes['sunrise_minute'], $diff);

        // 7. Auspicious & Inauspicious Muhurats (Rahu Kalam, Abhijit Muhurat)
        $muhurats = $this->calculateMuhurats($sunTimes['sunrise_minute'], $sunTimes['sunset_minute'], $dayOfWeek);

        $notes = "Abhijit Muhurat: {$muhurats['abhijit']} | Rahu Kalam: {$muhurats['rahu_kalam']} | Gulika Kalam: {$muhurats['gulika']} | Yamaganda: {$muhurats['yamaganda']}";

        return [
            'date'         => $date->toDateString(),
            'location'     => $location,
            'timezone'     => self::DEFAULT_TZ,
            'vaar'         => $vaar,
            'tithi'        => $full_tithi,
            'nakshatra'    => $nakshatra,
            'yoga'         => $yoga,
            'karana'       => $karana,
            'sunrise'      => $sunTimes['sunrise'],
            'sunset'       => $sunTimes['sunset'],
            'moonrise'     => $moonTimes['moonrise'],
            'moonset'      => $moonTimes['moonset'],
            'notes'        => $notes,
            'status'       => 'published',
            'published_at' => now(),
        ];
    }

    /**
     * Compute daily Rashifal predictions for all 12 zodiac signs for a given date.
     */
    public function computeRashifal(Carbon $date): array
    {
        $dayOfWeek = $date->dayOfWeek;
        $dayOfYear = $date->dayOfYear;
        $seed = $date->year * 1000 + $dayOfYear;

        $templates = [
            0 => [
                'theme' => 'Financial Growth & Family Blessings',
                'body'  => 'Today brings favorable planetary alignment for thoughtful decisions. Family matters receive positive harmony and divine support. In your professional routine, focus on thorough completion of pending projects rather than rushing into speculative ventures. Evening hours are ideal for prayer, meditation, and quiet reflection.',
            ],
            1 => [
                'theme' => 'Career Progress & Auspicious Initiatives',
                'body'  => 'Cosmic energies emphasize clear communication and initiative today. Colleagues and elders appreciate your sincere contributions. Stay grounded and maintain patience during midday meetings. A minor financial benefit or favorable news regarding long-term investments is likely. Donate white flowers or offer water to Surya Dev in the morning.',
            ],
            2 => [
                'theme' => 'Inner Peace & New Opportunities',
                'body'  => 'Auspicious vibrations surround creative and spiritual endeavors. Your confidence will help resolve an ongoing challenge with grace. Avoid unnecessary arguments in social circles. Keep hydrated and allocate time for family well-being. Chanting the Gayatri Mantra brings enhanced mental clarity.',
            ],
            3 => [
                'theme' => 'Health Vitality & Success in Partnerships',
                'body'  => 'An energetic and progressive day where diligence yields rewarding results. Collaborative work thrives under today’s lunar influence. Pay attentive care to diet and physical rest. A positive interaction with an esteemed mentor or Bhudev offers invaluable guidance for your future path.',
            ],
            4 => [
                'theme' => 'Wisdom, Learning & Spiritual Harmony',
                'body'  => 'Planetary aspects favor intellectual pursuits, Vedic study, and spiritual discussions. Financial transactions should be handled with standard vigilance. Loved ones provide reassurance and comfort. An auspicious time to visit a temple or perform evening Sandhya prayers.',
            ],
            5 => [
                'theme' => 'Stability, Auspicious News & Goodwill',
                'body'  => 'A peaceful day characterized by steady progress and benevolent connections. A pending undertaking gains fresh momentum. Maintain modesty and express gratitude to ancestors and teachers. Favorable times for planning domestic ceremonies and family celebrations.',
            ],
            6 => [
                'theme' => 'Courage, Accomplishment & Well-Being',
                'body'  => 'A dynamic day supporting determination and personal discipline. Past efforts begin showing tangible results. Nurture mutual understanding with close partners and respect traditional wisdom. Light a ghee lamp in the evening for tranquility and prosperity.',
            ],
        ];

        $results = [];

        foreach (self::ZODIAC_SIGNS as $sign => $meta) {
            $signSeed = $seed + crc32($sign);
            $templateIdx = abs($signSeed) % count($templates);
            $t = $templates[$templateIdx];

            $luckyColor = $meta['colors'][abs($signSeed) % count($meta['colors'])];
            $luckyNum = $meta['numbers'][abs($signSeed) % count($meta['numbers'])];

            $content = "{$t['body']}\n\n• Lucky Color: {$luckyColor}\n• Lucky Number: {$luckyNum}\n• Ruling Planet: {$meta['ruler']}\n• Vedic Sign: {$meta['hindi']}";

            $results[$sign] = [
                'sign'         => $sign,
                'date'         => $date->toDateString(),
                'title'        => "{$meta['hindi']} — {$t['theme']}",
                'content'      => $content,
                'status'       => 'published',
                'published_at' => now(),
            ];
        }

        return $results;
    }

    private function calculateSunTimes(Carbon $date, float $lat, float $lon): array
    {
        $dayOfYear = $date->dayOfYear;

        // Solar Declination (degrees)
        $decl = 23.45 * sin(deg2rad((360 / 365) * ($dayOfYear - 81)));

        // Hour angle (degrees)
        $cosH = -tan(deg2rad($lat)) * tan(deg2rad($decl));
        $cosH = max(-1.0, min(1.0, $cosH));
        $H = rad2deg(acos($cosH));

        // Solar noon in hours (IST +5.5 hours, Meridian 82.5° E)
        $noonHours = 12.0 + (82.5 - $lon) / 15.0;

        // Equation of time correction (minutes)
        $b = (360 / 365) * ($dayOfYear - 81);
        $eot = 9.87 * sin(deg2rad(2 * $b)) - 7.53 * cos(deg2rad($b)) - 1.5 * sin(deg2rad($b));
        $noonMinutes = ($noonHours * 60) - $eot;

        $halfDayMinutes = ($H / 15.0) * 60.0;
        $sunriseMin = (int) round($noonMinutes - $halfDayMinutes);
        $sunsetMin = (int) round($noonMinutes + $halfDayMinutes);

        return [
            'sunrise'        => $this->formatMinutes($sunriseMin),
            'sunset'         => $this->formatMinutes($sunsetMin),
            'sunrise_minute' => $sunriseMin,
            'sunset_minute'  => $sunsetMin,
        ];
    }

    private function calculateMoonTimes(Carbon $date, int $sunriseMin, float $elongation): array
    {
        // Moon rises approx 48-52 minutes later each day (offset based on lunar phase elongation)
        $phaseOffset = ($elongation / 360.0) * 1440.0;
        $moonriseMin = (int) round(fmod($sunriseMin + $phaseOffset + 1440.0, 1440.0));
        $moonsetMin = (int) round(fmod($moonriseMin + 720.0, 1440.0));

        return [
            'moonrise' => $this->formatMinutes($moonriseMin),
            'moonset'  => $this->formatMinutes($moonsetMin),
        ];
    }

    private function calculateMuhurats(int $sunriseMin, int $sunsetMin, int $dayOfWeek): array
    {
        $dayLength = max(600, $sunsetMin - $sunriseMin);
        $segment = $dayLength / 8.0;

        // Rahu Kalam segment by day (0=Sunday to 6=Saturday)
        // Sun: 8th, Mon: 2nd, Tue: 7th, Wed: 5th, Thu: 6th, Fri: 4th, Sat: 3rd
        $rahuSegments = [0 => 7, 1 => 1, 2 => 6, 3 => 4, 4 => 5, 5 => 3, 6 => 2];
        $yamSegments = [0 => 4, 1 => 3, 2 => 2, 3 => 1, 4 => 0, 5 => 6, 6 => 5];
        $gulikaSegments = [0 => 6, 1 => 5, 2 => 4, 3 => 3, 4 => 2, 5 => 1, 6 => 0];

        $rahuIdx = $rahuSegments[$dayOfWeek] ?? 1;
        $rahuStart = (int) round($sunriseMin + ($rahuIdx * $segment));
        $rahuEnd = (int) round($rahuStart + $segment);

        $yamIdx = $yamSegments[$dayOfWeek] ?? 3;
        $yamStart = (int) round($sunriseMin + ($yamIdx * $segment));
        $yamEnd = (int) round($yamStart + $segment);

        $gulikaIdx = $gulikaSegments[$dayOfWeek] ?? 5;
        $gulikaStart = (int) round($sunriseMin + ($gulikaIdx * $segment));
        $gulikaEnd = (int) round($gulikaStart + $segment);

        // Abhijit Muhurat: 8th Muhurat of the 15 Muhurats of the day (centered on solar midday)
        $muhuratLength = $dayLength / 15.0;
        $abhijitStart = (int) round($sunriseMin + (7 * $muhuratLength));
        $abhijitEnd = (int) round($abhijitStart + $muhuratLength);

        return [
            'abhijit'    => $this->formatMinutes($abhijitStart) . ' - ' . $this->formatMinutes($abhijitEnd),
            'rahu_kalam' => $this->formatMinutes($rahuStart) . ' - ' . $this->formatMinutes($rahuEnd),
            'yamaganda'  => $this->formatMinutes($yamStart) . ' - ' . $this->formatMinutes($yamEnd),
            'gulika'     => $this->formatMinutes($gulikaStart) . ' - ' . $this->formatMinutes($gulikaEnd),
        ];
    }

    private function formatMinutes(int $minutes): string
    {
        $minutes = fmod($minutes + 1440, 1440);
        $h24 = (int) floor($minutes / 60);
        $m = (int) ($minutes % 60);
        $ampm = $h24 >= 12 ? 'PM' : 'AM';
        $h12 = $h24 % 12;
        if ($h12 === 0) $h12 = 12;
        return sprintf('%02d:%02d %s', $h12, $m, $ampm);
    }
}
