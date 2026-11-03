<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BhudevInstagramPost extends Model
{
    protected $table = 'bhudev_instagram_posts';

    protected $fillable = [
        'bhudev_profile_id',
        'source',
        'ig_media_id',
        'url',
        'video_path',
        'media_url',
        'posted_at',
        'synced_at',
        'shortcode',
        'media_type',
        'caption',
        'thumbnail_url',
        'author_name',
        'is_active',
        'is_autoplay',
        'sort_order',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'is_autoplay' => 'boolean',
            'sort_order'  => 'integer',
            'metadata'    => 'array',
            'posted_at'   => 'datetime',
            'synced_at'   => 'datetime',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(BhudevProfile::class, 'bhudev_profile_id');
    }

    public function isVideoUpload(): bool
    {
        return !empty($this->video_path);
    }

    /**
     * Pulled automatically from the connected Instagram account (library sync).
     */
    public function isFromLibrary(): bool
    {
        return $this->source === 'api';
    }

    /**
     * Library video whose Instagram-hosted MP4 can be played natively.
     */
    public function isApiVideo(): bool
    {
        return $this->isFromLibrary()
            && strtoupper((string) data_get($this->metadata, 'media_type')) === 'VIDEO'
            && !empty($this->media_url);
    }

    public function hasNativeVideo(): bool
    {
        return $this->isVideoUpload() || $this->isApiVideo();
    }

    public function getVideoUrl(): ?string
    {
        if ($this->video_path) {
            return asset('storage/' . $this->video_path);
        }

        return $this->isApiVideo() ? $this->media_url : null;
    }

    /**
     * Still image for library photos / carousels (and poster for library videos).
     */
    public function getImageUrl(): ?string
    {
        if ($this->isFromLibrary() && !$this->isApiVideo() && $this->media_url) {
            return $this->media_url;
        }

        return $this->thumbnail_url ?: null;
    }

    public function getEmbedUrl(): string
    {
        $segment = $this->media_type === 'reel' ? 'reel' : 'p';

        return "https://www.instagram.com/{$segment}/{$this->shortcode}/embed/";
    }
}
