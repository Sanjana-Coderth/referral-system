<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
        'referral_code',
        'referred_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id = (string) Str::uuid();
            $user->referral_code = strtoupper(Str::random(8));
        });
    }

    // 🔗 Who referred this user
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    // 🔗 Users referred by this user
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    // 💰 Wallet relation
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }
}