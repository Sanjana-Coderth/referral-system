<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class TelegramController extends Controller
{
    /**
     * @OA\Get(
     *     path="/telegram-stats",
     *     summary="Get Telegram Group Members Count",
     *     tags={"Telegram"},
     *     operationId="telegramStats",
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
     *     @OA\Response(response=404, description="Not Found"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function stats(): JsonResponse
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $channel = env('TELEGRAM_CHANNEL');

        $response = Http::get(
            "https://api.telegram.org/bot{$token}/getChatMembersCount",
            [
                'chat_id' => $channel
            ]
        );

        return response()->json([
            'members' => $response->json()['result'] ?? 0
        ]);
    }
}