<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\WalletTransaction;
use OpenApi\Annotations as OA;

class WalletController extends Controller
{
    /**
     * @OA\Get(
     *     path="/wallet",
     *     summary="Get Wallet Balance",
     *     tags={"Wallet"},
     *     operationId="getWalletBalance",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Wallet balance fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="wallet_balance", type="number", example=150)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function balance(Request $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'wallet_balance' => $request->user()->wallet_balance
        ]);
    }

    /**
     * @OA\Get(
     *     path="/wallet-transactions",
     *     summary="Get Wallet Transactions",
     *     tags={"Wallet"},
     *     operationId="getWalletTransactions",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Wallet transactions fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="string", example="uuid"),
     *                     @OA\Property(property="amount", type="number", example=100),
     *                     @OA\Property(property="type", type="string", example="credit"),
     *                     @OA\Property(property="description", type="string", example="Referral Bonus"),
     *                     @OA\Property(property="created_at", type="string", example="2026-04-03 10:00:00")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function transactions(Request $request): JsonResponse
    {
        $transactions = WalletTransaction::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $transactions
        ]);
    }
}