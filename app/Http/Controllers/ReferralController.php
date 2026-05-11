<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Services\ReferralService;

use OpenApi\Annotations as OA;

class ReferralController extends Controller
{
    public function __construct(
        protected ReferralService $referralService
    ) {}

    /**
     * @OA\Get(
     *     path="/referrals",
     *     summary="Get Referral List",
     *     tags={"Referral"},
     *     security={{"Bearer": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Referral list fetched successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->referralService
            ->getReferrals($request->user());

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * @OA\Get(
     *     path="/referral-tree",
     *     summary="Get First Level Referral Tree",
     *     tags={"Referral"},
     *     security={{"Bearer": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Referral tree fetched successfully"
     *     )
     * )
     */
    public function tree(Request $request): JsonResponse
    {
        $data = $this->referralService
            ->getReferralTree($request->user());

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
    
}