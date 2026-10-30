<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Service extends Model { protected $fillable=['name','slug','description','is_active']; protected function casts(): array { return ['is_active'=>'boolean']; } public function bhudevs(): BelongsToMany { return $this->belongsToMany(BhudevProfile::class,'bhudev_service')->withTimestamps(); } }
