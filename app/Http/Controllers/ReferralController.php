<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\ReferralService;
use OpenApi\Annotations as OA;

class ReferralController extends Controller
{
    public function __construct(
        protected referralService $referralService
    ){}

    /**
     * @OA\Get(
     *     path="/referrals",
     *     summary="Get Referral List",
     *     tags={"Referral"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Referral list fetched successfully")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->referralService->getReferrals($request->user());

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}

