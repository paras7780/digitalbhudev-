<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BhudevImage extends Model { protected function casts(): array { return ['is_approved'=>'boolean']; } protected $fillable=['bhudev_profile_id','path','alt_text','sort_order','is_approved']; public function profile(): BelongsTo { return $this->belongsTo(BhudevProfile::class,'bhudev_profile_id'); } }
