<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    /**
     * @OA\Parameter(
     *     parameter="login_email",
     *     name="email",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Parameter(
     *     parameter="login_password",
     *     name="password",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     * )
     *
     * @OA\Parameter(
     *     parameter="login_remember",
     *     name="remember_me",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="boolean")
     * )
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'remember_me' => 'nullable|boolean',
        ];
    }
}
