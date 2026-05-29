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
     *     operationId="dashboardData",
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
     *     operationId="dashboardChartData",
     *     security={{"Bearer": {}}},
     *
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         required=true,
     *         description="Chart type",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"Day","Month","Year"},
     *             example="Month"
     *         )
     *     ),
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
     *     operationId="recentUsers",
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
    public function recentUsers(): JsonResponse
    {
        $users = $this->dashboardService->recentUsers();

        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }
}
