<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @OA\Parameter(
     *      parameter="name",
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(type="string")
     * )
     *
     * @OA\Parameter(
     *      parameter="usdt_wallet_address",
     *      name="usdt_wallet_address",
     *      in="query",
     *      required=false,
     *      @OA\Schema(type="string")
     * )
     *
     * @OA\Parameter(
     *      parameter="bsc_wallet_address",
     *      name="bsc_wallet_address",
     *      in="query",
     *      required=false,
     *      @OA\Schema(type="string")
     * )
     *
     * @OA\RequestBody(
     *      request="image",
     *      @OA\MediaType(
     *          mediaType="multipart/form-data",
     *          @OA\Schema(
     *              type="object",
     *              @OA\Property(
     *                  property="image",
     *                  type="string",
     *                  format="binary"
     *              ),
     *          )
     *      )
     * )
     */
    public function rules(): array
    {
        return [

            'name' => 'required|string|max:255',

            'usdt_wallet_address' =>
                'nullable|string|max:255',

            'bsc_wallet_address' =>
                'nullable|string|max:255',

            'image' =>
                'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}