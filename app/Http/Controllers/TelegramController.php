<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class TelegramController extends Controller
{
    /**
     * @OA\Get(
     *     path="/telegram-stats",
     *     summary="Get Telegram Group and Channel Members Count",
     *     tags={"Telegram"},
     *     security={{"Bearer": {}}},
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
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     *     @OA\Response(response=422, description="Unprocessable Entity"),
     *     @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function stats(): JsonResponse
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        // FIX: separate variables (NEVER duplicate .env keys)
        $groupChatId = env('TELEGRAM_GROUP');
        $channelChatId = env('TELEGRAM_CHANNEL');

        $groupCount = 0;
        $channelCount = 0;

        if ($groupChatId) {
            $groupRes = Http::get(
                "https://api.telegram.org/bot{$token}/getChatMembersCount",
                ['chat_id' => $groupChatId]
            );

            $groupCount = $groupRes->json()['result'] ?? 0;
        }

        if ($channelChatId) {
            $channelRes = Http::get(
                "https://api.telegram.org/bot{$token}/getChatMembersCount",
                ['chat_id' => $channelChatId]
            );

            $channelCount = $channelRes->json()['result'] ?? 0;
        }

        return response()->json([
            'group_members' => $groupCount,
            'channel_members' => $channelCount,
        ]);
    }


    // public function twitter()
    // {
    //     $token = env('TWITTER_BEARER_TOKEN');
    //     $username = env('TWITTER_USERNAME');

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //     ])->get(
    //         "https://api.twitter.com/2/users/by/username/$username",
    //         [
    //             'user.fields' => 'public_metrics'
    //         ]
    //     );

    //     return response()->json($response->json());
    // }
}
