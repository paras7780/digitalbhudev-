<?php

namespace App\Support;

/**
 * Small helpers for the SEO head tags and structured data.
 */
class Seo
{
    /**
     * Make a URL absolute. asset() returns a bare path when ASSET_URL is a path (as on the
     * local XAMPP setup), but Open Graph images and schema.org URLs must be absolute.
     */
    public static function absolute(?string $url): ?string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return null;
        }

        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        if (str_starts_with($url, '//')) {
            return request()->getScheme() . ':' . $url;
        }

        return request()->getSchemeAndHttpHost() . '/' . ltrim($url, '/');
    }

    /**
     * Blade escapes inline @section values, so decode them back to plain text before the
     * meta tags escape them once more.
     */
    public static function text(?string $value): string
    {
        $value = html_entity_decode(strip_tags((string) $value), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\s+/u', ' ', $value) ?? '');
    }

    /**
     * The site's own root URL with a trailing slash (includes the sub-folder when the app is
     * served from one, and the Pandit's domain on a tenant site).
     */
    public static function siteUrl(): string
    {
        return rtrim(url('/'), '/') . '/';
    }

    public const JSON_FLAGS = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP;
}
