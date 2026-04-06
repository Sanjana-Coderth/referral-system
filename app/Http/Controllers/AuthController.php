<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use OpenApi\Annotations as OA;

//check
class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="User Login",
     *     tags={"Auth"},
     *     operationId="loginUser",
     *
     *     @OA\Parameter(ref="#/components/parameters/login_email"),
     *     @OA\Parameter(ref="#/components/parameters/login_password"),
     *     @OA\Parameter(ref="#/components/parameters/login_remember"),
     *
     *     @OA\Response(response=200, description="User login successfully")
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $result = $this->authService->login($data);

        return response()->json([
            'message' => $result['message'],
            ...$result
        ]);
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="User Register",
     *     tags={"Auth"},
     *     operationId="registerUser",
     *
     *     @OA\Parameter(ref="#/components/parameters/register_name"),
     *     @OA\Parameter(ref="#/components/parameters/register_email"),
     *     @OA\Parameter(ref="#/components/parameters/register_password"),
     *     @OA\Parameter(ref="#/components/parameters/register_password_confirmation"),
     *     @OA\Parameter(ref="#/components/parameters/register_referral"),
     *
     *     @OA\Response(response=201, description="User registered successfully")
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'message' => $result['message'],
            'data' => $result['data']
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/refresh-token",
     *     summary="Refresh Access Token",
     *     tags={"Auth"},
     *     operationId="refreshToken",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(response=200, description="New access token generated"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function refreshToken(Request $request): JsonResponse
    {
        return response()->json(
            $this->authService->refreshAccessToken($request)
        );
    }

    /**
     * @OA\Post(
     *     path="/forgot-password",
     *     summary="Forgot Password",
     *     tags={"Auth"},
     *     operationId="forgotPassword",
     *
     *     @OA\Parameter(ref="#/components/parameters/forgot_email"),
     *
     *     @OA\Response(response=200, description="Reset link sent successfully")
     * )
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        return response()->json(
            $this->authService->forgotPassword($request->email)
        );
    }

    /**
     * @OA\Post(
     *     path="/reset-password",
     *     summary="Reset Password",
     *     tags={"Auth"},
     *     operationId="resetPassword",
     *
     *     @OA\Parameter(ref="#/components/parameters/reset_email"),
     *     @OA\Parameter(ref="#/components/parameters/reset_token"),
     *     @OA\Parameter(ref="#/components/parameters/reset_password"),
     *     @OA\Parameter(ref="#/components/parameters/reset_password_confirmation"),
     *
     *     @OA\Response(response=200, description="Password reset successful")
     * )
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        return response()->json(
            $this->authService->resetPasswordWeb($request->all())
        );
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="User Logout",
     *     tags={"Auth"},
     *     operationId="logoutUser",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(response=200, description="User logged out successfully")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}