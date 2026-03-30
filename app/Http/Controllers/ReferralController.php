<?php

namespace App\Http\Controllers;

use App\Services\ReferralService;

class ReferralController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    public function myReferrals()
    {
        return response()->json(
            $this->referralService->getMyReferrals(auth()->id())
        );
    }
}