<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Referral extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'referred_user_id',
        'reward'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($referral) {
            $referral->id = (string) Str::uuid();
        });
    }

    // 🔗 Referrer (who invited)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // 🔗 Referred user
    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}