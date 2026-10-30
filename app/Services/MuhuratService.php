<?php

namespace App\Services;

class MuhuratService
{
    /**
     * Curated Shubh Muhurats for ceremonies.
     */
    public static function all(): array
    {
        return [
            'vivah' => [
                'category' => 'Vivah Sanskar (Marriage)',
                'category_gu' => 'લગ્ન શુભ મુહૂર્ત',
                'description' => 'Auspicious wedding dates calculated as per Vedic astrology considering Guru and Shukra Tara Asta.',
                'icon' => '💍',
                'dates' => [
                    ['date' => '2026-11-18', 'day' => 'Wednesday', 'nakshatra' => 'Uttara Phalguni', 'tithi' => 'Shukla Dashami', 'time' => '10:45 AM to 02:15 PM'],
                    ['date' => '2026-11-22', 'day' => 'Sunday', 'nakshatra' => 'Rohini', 'tithi' => 'Shukla Chaturdashi', 'time' => '07:30 AM to 11:50 AM'],
                    ['date' => '2026-11-27', 'day' => 'Friday', 'nakshatra' => 'Mrigashira', 'tithi' => 'Krishna Tritiya', 'time' => '02:20 PM to 06:40 PM'],
                    ['date' => '2026-12-04', 'day' => 'Friday', 'nakshatra' => 'Hasta', 'tithi' => 'Krishna Dashami', 'time' => '08:15 AM to 12:30 PM'],
                    ['date' => '2026-12-09', 'day' => 'Wednesday', 'nakshatra' => 'Anuradha', 'tithi' => 'Shukla Pratipada', 'time' => '11:00 AM to 03:45 PM'],
                    ['date' => '2026-12-14', 'day' => 'Monday', 'nakshatra' => 'Uttara Ashadha', 'tithi' => 'Shukla Panchami', 'time' => '09:00 AM to 01:20 PM'],
                ]
            ],
            'griha_pravesh' => [
                'category' => 'Griha Pravesh (House Warming)',
                'category_gu' => 'ગૃહ પ્રવેશ મુહૂર્ત',
                'description' => 'Auspicious times for entering a new home to invite Vastu Purush blessings and peace.',
                'icon' => '🏡',
                'dates' => [
                    ['date' => '2026-11-04', 'day' => 'Wednesday', 'nakshatra' => 'Uttara Bhadrapada', 'tithi' => 'Shukla Ekadashi', 'time' => '06:40 AM to 10:15 AM'],
                    ['date' => '2026-11-12', 'day' => 'Thursday', 'nakshatra' => 'Moola', 'tithi' => 'Shukla Tritiya', 'time' => '07:15 AM to 11:30 AM'],
                    ['date' => '2026-11-20', 'day' => 'Friday', 'nakshatra' => 'Revati', 'tithi' => 'Shukla Dwadashi', 'time' => '08:00 AM to 12:00 PM'],
                    ['date' => '2026-12-02', 'day' => 'Wednesday', 'nakshatra' => 'Uttara Phalguni', 'tithi' => 'Krishna Ashtami', 'time' => '06:50 AM to 10:45 AM'],
                    ['date' => '2026-12-11', 'day' => 'Friday', 'nakshatra' => 'Shravana', 'tithi' => 'Shukla Tritiya', 'time' => '07:45 AM to 11:15 AM'],
                ]
            ],
            'vahan' => [
                'category' => 'Vehicle Purchase (Vahan Kharidi)',
                'category_gu' => 'વાહન ખરીદી શુભ મુહૂર્ત',
                'description' => 'Auspicious Choghadiya and Nakshatra for purchasing cars, two-wheelers, and commercial vehicles.',
                'icon' => '🚗',
                'dates' => [
                    ['date' => '2026-11-01', 'day' => 'Sunday', 'nakshatra' => 'Pushya / Ashlesha', 'tithi' => 'Shukla Ashtami', 'time' => 'Amrut: 09:20 AM - 10:45 AM'],
                    ['date' => '2026-11-08', 'day' => 'Sunday', 'nakshatra' => 'Chitra', 'tithi' => 'Shukla Purnima', 'time' => 'Shubh: 07:45 AM - 09:15 AM'],
                    ['date' => '2026-11-15', 'day' => 'Sunday', 'nakshatra' => 'Dhanishta', 'tithi' => 'Krishna Saptami', 'time' => 'Labh: 01:30 PM - 03:00 PM'],
                    ['date' => '2026-11-23', 'day' => 'Monday', 'nakshatra' => 'Rohini', 'tithi' => 'Shukla Purnima', 'time' => 'Amrut: 06:45 AM - 08:15 AM'],
                    ['date' => '2026-12-06', 'day' => 'Sunday', 'nakshatra' => 'Swati', 'tithi' => 'Krishna Dwadashi', 'time' => 'Shubh: 09:15 AM - 10:45 AM'],
                ]
            ],
            'namkaran' => [
                'category' => 'Namkaran & Mundan Sanskar',
                'category_gu' => 'નામકરણ અને મુંડન સંસ્કાર',
                'description' => 'Sacred timing for baby naming ceremony and first tonsure (Chaula karma).',
                'icon' => '👶',
                'dates' => [
                    ['date' => '2026-11-06', 'day' => 'Friday', 'nakshatra' => 'Hasta', 'tithi' => 'Shukla Trayodashi', 'time' => '08:30 AM to 12:15 PM'],
                    ['date' => '2026-11-19', 'day' => 'Thursday', 'nakshatra' => 'Uttara Phalguni', 'tithi' => 'Shukla Ekadashi', 'time' => '07:15 AM to 11:00 AM'],
                    ['date' => '2026-11-29', 'day' => 'Sunday', 'nakshatra' => 'Punarvasu', 'tithi' => 'Krishna Panchami', 'time' => '09:00 AM to 01:00 PM'],
                    ['date' => '2026-12-10', 'day' => 'Thursday', 'nakshatra' => 'Moola', 'tithi' => 'Shukla Dwitiya', 'time' => '08:00 AM to 11:30 AM'],
                ]
            ],
        ];
    }
}
