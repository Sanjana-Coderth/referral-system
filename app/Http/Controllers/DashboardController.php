<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Referral;
use App\Models\WalletTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        return response()->json([
            'total_referrals' => Referral::where('user_id', $userId)->count(),
            'total_earnings' => WalletTransaction::where('user_id', $userId)
                ->where('type', 'credit')
                ->sum('amount'),
            'wallet_balance' => Wallet::where('user_id', $userId)->value('balance'),
            'referrals' => Referral::where('user_id', $userId)->get()
        ]);
    }
}