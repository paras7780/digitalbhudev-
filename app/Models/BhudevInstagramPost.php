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
        'shortcode',
        'media_type',
        'caption',
        'thumbnail_url',
        'author_name',
        'is_active',
        'sort_order',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'sort_order'  => 'integer',
            'metadata'    => 'array',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(BhudevProfile::class, 'bhudev_profile_id');
    }

    public function getEmbedUrl(): string
    {
        return "https://www.instagram.com/{$this->media_type}/{$this->shortcode}/embed/";
    }
}
