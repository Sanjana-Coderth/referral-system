<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\TopReferralService;
use App\Http\Requests\TopReferralRequest;

use App\Services\ReferralService;

use OpenApi\Annotations as OA;

class ReferralController extends Controller
{
    public function __construct(
        protected ReferralService $referralService,
        protected TopReferralService $topReferralService
    ) {}

    /**
     * @OA\Get(
     *     path="/referrals",
     *     summary="Get Referral List",
     *     tags={"Referral"},
     *     operationId="referralList",
     *     security={{"Bearer": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     @OA\Response(response=422, description="Unprocessable Entity"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->referralService->getReferrals($request->user());
        return response()->json(['data' => $data]);
    }

    /**
     * @OA\Get(
     *     path="/referral-tree",
     *     summary="Get First Level Referral Tree",
     *     tags={"Referral"},
     *     operationId="referralTree",
     *     security={{"Bearer": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     @OA\Response(response=422, description="Unprocessable Entity"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function tree(Request $request): JsonResponse
    {
        $data = $this->referralService->getReferralTree($request->user());
        return response()->json(['data' => $data]);
    }

    /**
     * @OA\Get(
     *      path="/top-referrals",
     *      tags={"Referral"},
     *      summary="Top Referrals List",
     *      operationId="topReferrals",
     *      security={{"Bearer": {}}},
     *
     *      @OA\Parameter(
     *          name="per_page",
     *          in="query",
     *          description="Pagination per page",
     *          required=false,
     *
     *          @OA\Schema(
     *              type="integer",
     *              example=10
     *          )
     *      ),
     *
     *      @OA\Response(response=200, description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      ),
     *
     *      @OA\Response(response=400, description="Bad Request"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */

    public function topReferrals(TopReferralRequest $request)
    {
        $data = $this->topReferralService->getTopReferrals($request->validated());
        return response()->json(['message' => 'Top referrals fetched successfully.', 'data' => $data]);
    }
}
