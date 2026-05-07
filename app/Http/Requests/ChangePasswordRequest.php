<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @OA\Parameter(
     *      parameter="current_password",
     *      name="current_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(type="string")
     * )
     *
     * @OA\Parameter(
     *      parameter="password",
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(type="string")
     * )
     *
     * @OA\Parameter(
     *      parameter="password_confirmation",
     *      name="password_confirmation",
     *      in="query",
     *      required=true,
     *      @OA\Schema(type="string")
     * )
     */
    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'current_password'
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults()
            ],
        ];
    }
}