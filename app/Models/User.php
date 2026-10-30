<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'is_active', 'email_verified_at'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_active' => 'boolean']; }
    public function bhudevProfile(): HasOne { return $this->hasOne(BhudevProfile::class); }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isBhudev(): bool { return $this->role === 'bhudev'; }
}
