<?php

namespace App\Support;

/**
 * Builds WhatsApp click-to-chat links. The platform support number comes from
 * SUPPORT_WHATSAPP in .env (config('app.support_whatsapp')); when it is not set,
 * every helper returns null so callers can hide the button or fall back to a form.
 */
class WhatsApp
{
    /**
     * Keep digits only and assume India (+91) for bare 10-digit numbers.
     */
    public static function normalize(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number);

        if ($digits === '' || $digits === null) {
            return null;
        }

        if (strlen($digits) === 10) {
            $digits = '91' . $digits;
        }

        return $digits;
    }

    public static function supportNumber(): ?string
    {
        return self::normalize(config('app.support_whatsapp'));
    }

    public static function url(?string $number, string $message = ''): ?string
    {
        $digits = self::normalize($number);

        if (!$digits) {
            return null;
        }

        return 'https://wa.me/' . $digits . ($message !== '' ? '?text=' . urlencode($message) : '');
    }

    public static function supportUrl(string $message = ''): ?string
    {
        return self::url(self::supportNumber(), $message);
    }
}
