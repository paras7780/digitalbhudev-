<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BhudevSamagriCard extends Model
{
    protected $fillable = [
        'bhudev_profile_id',
        'pooja_slug',
        'pooja_title',
        'mantra_line',
        'firm_title',
        'left_priest_name',
        'left_priest_phone',
        'right_priest_name',
        'right_priest_phone',
        'column1_items',
        'column2_items',
        'yajman_name',
        'locale',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(BhudevProfile::class, 'bhudev_profile_id');
    }
}
