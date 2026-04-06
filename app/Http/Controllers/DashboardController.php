<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\DashboardService;
use OpenApi\Annotations as OA;

class DashboardController extends Controller
{
    public function __construct(
        protected dashboardService $dashboardService
    ){}

    /**
     * @OA\Get(
     *     path="/dashboard",
     *     summary="Get Dashboard Data",
     *     tags={"Dashboard"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard data fetched successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $data = $this->dashboardService->getDashboardData($request->user());

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}