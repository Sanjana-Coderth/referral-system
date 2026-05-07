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
     *      parameter="first_name",
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(type="string")
     * )
     *
     * @OA\Parameter(
     *      parameter="last_name",
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(type="string")
     * )
     *
     * @OA\Parameter(
     *      parameter="email",
     *      name="email",
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

            'first_name' => 'required|string|max:255',

            'last_name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'unique:users,email,' . request()->user()->id,
            ],

            'usdt_wallet_address' => 'nullable|string|max:255',

            'bsc_wallet_address' => 'nullable|string|max:255',

            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
