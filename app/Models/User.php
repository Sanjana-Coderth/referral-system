<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'referral_code', 
        'referred_by',    
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 🔗 Relationship: who referred this user
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * 🔗 Relationship: users referred by this user
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }
}