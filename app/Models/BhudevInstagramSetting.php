<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BhudevInstagramSetting extends Model
{
    protected $table = 'bhudev_instagram_settings';

    protected $fillable = [
        'bhudev_profile_id',
        'is_enabled',
        'auto_play',
        'connection_status',
        'account_username',
        'account_id',
        'access_token',
        'token_type',
        'token_expires_at',
        'app_id',
        'app_secret',
        'last_sync_at',
        'last_error',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled'        => 'boolean',
            'auto_play'         => 'boolean',
            'token_expires_at'  => 'datetime',
            'last_sync_at'      => 'datetime',
            'access_token'      => 'encrypted',
            'app_secret'        => 'encrypted',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(BhudevProfile::class, 'bhudev_profile_id');
    }

    public function isConnected(): bool
    {
        return $this->connection_status === 'connected';
    }

    public function getMaskedToken(): ?string
    {
        if (empty($this->access_token)) {
            return null;
        }

        $length = strlen($this->access_token);
        if ($length <= 8) {
            return '••••••••';
        }

        return substr($this->access_token, 0, 4) . '••••••••' . substr($this->access_token, -4);
    }
}
