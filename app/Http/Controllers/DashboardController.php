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
    ) {}

    /**
     * @OA\Get(
     *     path="/dashboard",
     *     summary="Get Dashboard Data",
     *     tags={"Dashboard"},
     *     security={{"Bearer": {}}},
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

    /**
     * @OA\Get(
     *     path="/dashboard-chart/{type}",
     *     summary="Get Dashboard Chart Data",
     *     tags={"Dashboard"},
     *     security={{"Bearer": {}}},
     *
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         required=true,
     *         description="Chart type",
     *         @OA\Schema(
     *             type="string",
     *             enum={"Day","Month","Year"}
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Chart data fetched successfully"
     *     )
     * )
     */
    public function chart(string $type): JsonResponse
    {
        $data = $this->dashboardService->chart($type);

        return response()->json($data);
    }

    /**
     * @OA\Get(
     *     path="/recent-users",
     *     summary="Get Recent Users",
     *     tags={"Dashboard"},
     *     security={{"Bearer": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Recent users fetched successfully"
     *     )
     * )
     */
    public function recentUsers(): JsonResponse
    {
        $users = $this->dashboardService->recentUsers();

        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }
}
