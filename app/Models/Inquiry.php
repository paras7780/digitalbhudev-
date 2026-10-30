<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'type',
        'city',
        'pooja_service',
        'preferred_date',
        'bhudev_profile_id',
        'message',
        'status',
        'assigned_to',
        'admin_note',
        'ip_hash',
    ];

    protected $casts = [
        'preferred_date' => 'date',
    ];

    public function bhudevProfile(): BelongsTo
    {
        return $this->belongsTo(BhudevProfile::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
