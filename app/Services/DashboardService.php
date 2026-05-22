<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDashboardData(User $user)
    {
        $totalReferrals = User::where('referred_by', $user->id)->count();

        $referralEarnings = WalletTransaction::where('user_id', $user->id)
            ->where('description', 'LIKE', '%Referral Bonus%')
            ->sum('amount');

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return [
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'referral_code' => $user->referral_code,
                'email_verified_at' =>
                    $user->email_verified_at,
            ],
            'wallet' => [
                'balance' => $user->wallet_balance,
                'referral_earnings' => $referralEarnings,
            ],
            'referrals' => [
                'total_referrals' => $totalReferrals,
            ],
            'recent_transactions' => $transactions,

            'total_users' => User::count(),

            'recent_users' => $this->recentUsers(),
        ];
    }

    public function chart(string $type)
    {
        // MONTH WISE USERS
        if ($type == 'Month') {

            $users = User::select(
                DB::raw("MONTH(created_at) as month"),
                DB::raw("COUNT(*) as total")
            )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            $data = array_fill(0, 12, 0);

            foreach ($users as $user) {
                $data[$user->month - 1] = $user->total;
            }

            return [
                'labels' => $labels,
                'data' => $data,
            ];
        }

        // DAY WISE USERS
        if ($type == 'Day') {

            $users = User::select(
                DB::raw("DAYNAME(created_at) as day"),
                DB::raw("COUNT(*) as total")
            )
                ->groupBy('day')
                ->get();

            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $shortDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

            $data = [];

            foreach ($days as $day) {

                $found = $users->firstWhere('day', $day);

                $data[] = $found ? $found->total : 0;
            }

            return [
                'labels' => $shortDays,
                'data' => $data,
            ];
        }

        // YEAR WISE USERS
        if ($type == 'Year') {

            $users = User::select(
                DB::raw("YEAR(created_at) as year"),
                DB::raw("COUNT(*) as total")
            )
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            return [
                'labels' => $users->pluck('year'),
                'data' => $users->pluck('total'),
            ];
        }

        return [
            'labels' => [],
            'data' => [],
        ];
    }

    public function recentUsers()
    {
        return User::latest()
            ->take(10)
            ->get([
                'id',
                'name',
                'email',
                'created_at',
                'last_login_at'
            ]);
    }
}
