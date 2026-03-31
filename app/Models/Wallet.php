<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'balance'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wallet) {
            $wallet->id = (string) Str::uuid();
        });
    }

    // 🔗 Belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 💰 Transactions
    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}