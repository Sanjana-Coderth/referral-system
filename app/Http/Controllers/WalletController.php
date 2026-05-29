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
    public function balance(Request $request): JsonResponse
    {
        return response()->json(['wallet_balance' => $request->user()->wallet_balance]);
    }

    /**
     * @OA\Get(
     *     path="/wallet-transactions",
     *     summary="Get Wallet Transactions",
     *     tags={"Wallet"},
     *     operationId="getWalletTransactions",
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
    public function transactions(Request $request): JsonResponse
    {
        $transactions = WalletTransaction::where('user_id', $request->user()->id)->latest()->get();
        return response()->json(['data' => $transactions]);
    }
}
