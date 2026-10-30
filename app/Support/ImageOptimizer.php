<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ImageOptimizer
{
    /**
     * Store an uploaded image, optimizing/resizing it when PHP GD is available,
     * or gracefully falling back to standard disk storage when GD is not loaded.
     */
    public static function store(UploadedFile $file, string $directory): string
    {
        $directory = trim($directory, '/');

        if (!extension_loaded('gd')) {
            return $file->store($directory, 'public');
        }

        try {
            $raw = file_get_contents($file->getRealPath());
            if ($raw === false) {
                return $file->store($directory, 'public');
            }

            $source = @imagecreatefromstring($raw);
            if (!$source) {
                return $file->store($directory, 'public');
            }

            $width = imagesx($source);
            $height = imagesy($source);
            $max = 1600;

            if ($width <= $max && $height <= $max) {
                // Keep original if smaller than max boundary
                imagedestroy($source);
                return $file->store($directory, 'public');
            }

            $scale = min(1, $max / max($width, $height));
            $newW = max(1, (int) round($width * $scale));
            $newH = max(1, (int) round($height * $scale));

            $target = imagecreatetruecolor($newW, $newH);
            imagealphablending($target, false);
            imagesavealpha($target, true);
            $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
            imagefilledrectangle($target, 0, 0, $newW, $newH, $transparent);
            imagecopyresampled($target, $source, 0, 0, 0, 0, $newW, $newH, $width, $height);

            ob_start();
            $format = function_exists('imagewebp') ? 'webp' : 'png';
            if ($format === 'webp') {
                imagewebp($target, null, 82);
            } else {
                imagepng($target, null, 7);
            }
            $contents = ob_get_clean();

            imagedestroy($source);
            imagedestroy($target);

            $path = $directory . '/' . bin2hex(random_bytes(16)) . '.' . $format;
            Storage::disk('public')->put($path, $contents);

            return $path;
        } catch (Throwable $e) {
            // Graceful fallback on any unexpected error
            return $file->store($directory, 'public');
        }
    }
}
