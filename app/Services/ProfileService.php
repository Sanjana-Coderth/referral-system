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

        $user->fill($data);

        $user->save();

        return $user;
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
