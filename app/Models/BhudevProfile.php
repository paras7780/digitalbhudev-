<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class BhudevProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'display_name',
        'slug',
        'city',
        'state',
        'address',
        'show_address',
        'show_mobile',
        'show_email',
        'languages',
        'expertise',
        'experience_years',
        'qualification',
        'service_area',
        'bio',
        'availability',
        'photo_path',
        'seo_title',
        'seo_description',
        'status',
        'preferred_locale',
        'is_visible',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by'
    ];
    protected function casts(): array { return ['languages'=>'array','show_address'=>'boolean','show_mobile'=>'boolean','show_email'=>'boolean','is_visible'=>'boolean','reviewed_at'=>'datetime']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function services(): BelongsToMany { return $this->belongsToMany(Service::class,'bhudev_service')->withTimestamps(); }
    public function images(): HasMany { return $this->hasMany(BhudevImage::class)->orderBy('sort_order'); }
    public function publicImages(): HasMany { return $this->hasMany(BhudevImage::class)->where('is_approved', true)->orderBy('sort_order'); }
    public function revisions(): HasMany { return $this->hasMany(ProfileRevision::class)->latest(); }
    public function statusHistory(): HasMany { return $this->hasMany(BhudevStatusHistory::class)->latest(); }
    public function inquiries(): HasMany { return $this->hasMany(Inquiry::class)->latest(); }
    public function domains(): HasMany { return $this->hasMany(BhudevDomain::class)->latest(); }
    public function activeDomain(): \Illuminate\Database\Eloquent\Relations\HasOne { return $this->hasOne(BhudevDomain::class)->where('status', 'active'); }
    public function samagriCards(): HasMany { return $this->hasMany(BhudevSamagriCard::class)->latest(); }
    public function instagramSetting(): \Illuminate\Database\Eloquent\Relations\HasOne { return $this->hasOne(BhudevInstagramSetting::class); }
    public function instagramPosts(): HasMany { return $this->hasMany(BhudevInstagramPost::class)->orderBy('sort_order')->latest(); }
    public function activeInstagramPosts(): HasMany { return $this->hasMany(BhudevInstagramPost::class)->where('is_active', true)->orderBy('sort_order')->latest(); }
    public function scopePubliclyVisible(Builder $query): Builder { return $query->where('status','approved')->where('is_visible',true)->whereHas('user',fn($q)=>$q->where('is_active',true)->where('role','bhudev')); }
}
