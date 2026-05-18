<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    // GET PROFILE
    public function getProfile(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'wallet_balance' => $user->wallet_balance,
            'referral_code' => $user->referral_code,
            'created_at' => $user->created_at,
        ];
    }

    // UPDATE PROFILE
    public function updateProfile(array $data)
    {
        $user = request()->user();

        if (request()->hasFile('image')) {

            $image = request()
                ->file('image')
                ->store('profile', 'public');

            $data['image'] = $image;
        }

        $user->update([

            'name' => $data['name'],

            'usdt_wallet_address' =>
            $data['usdt_wallet_address'] ?? null,

            'bsc_wallet_address' =>
            $data['bsc_wallet_address'] ?? null,

            'image' => $data['image'] ?? $user->image,
        ]);

        return $user->fresh();
    }

    // CHANGE PASSWORD
    public function changePassword(array $data): void
    {
        $user = request()->user();

        $user->update([
            'password' => Hash::make($data['password'])
        ]);
    }
}
