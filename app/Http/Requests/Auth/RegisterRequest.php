<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Parameter(
 *     parameter="register_name",
 *     name="name",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="register_email",
 *     name="email",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="register_password",
 *     name="password",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="register_password_confirmation",
 *     name="password_confirmation",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="register_referral",
 *     name="referred_by_code",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="refresh_token",
 *     name="refresh_token",
 *     in="query",
 *     required=false,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="forgot_email",
 *     name="email",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="reset_email",
 *     name="email",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="reset_token",
 *     name="token",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="reset_password",
 *     name="password",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="reset_password_confirmation",
 *     name="password_confirmation",
 *     in="query",
 *     required=true,
 *     @OA\Schema(type="string")
 * )
 */
class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'referred_by_code' => 'nullable|string|exists:users,referral_code',
        ];
    }
}
