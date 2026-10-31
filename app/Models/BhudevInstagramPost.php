<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BhudevInstagramPost extends Model
{
    protected $table = 'bhudev_instagram_posts';

    protected $fillable = [
        'bhudev_profile_id',
        'url',
        'video_path',
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

    public function getVideoUrl(): ?string
    {
        return $this->video_path ? asset('storage/' . $this->video_path) : null;
    }

    public function getEmbedUrl(): string
    {
        return "https://www.instagram.com/{$this->media_type}/{$this->shortcode}/embed/";
    }
}
