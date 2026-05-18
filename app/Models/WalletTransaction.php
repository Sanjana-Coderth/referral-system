<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WalletTransaction extends Model
{
    use HasUuids;
    protected $fillable = [
        'user_id',
        'from_id',
        'from_email',
        'to_id',
        'to_email',
        'amount',
        'type',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
