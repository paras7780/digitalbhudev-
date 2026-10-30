<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BhudevStatusHistory extends Model
{
    protected $fillable = [
        'bhudev_profile_id',
        'changed_by',
        'from_status',
        'to_status',
        'reason',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(BhudevProfile::class, 'bhudev_profile_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
