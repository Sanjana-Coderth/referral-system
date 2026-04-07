<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

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
     *          @OA\Response(response=200, description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      ),
     *      @OA\Response(response=400, description="Bad Request"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password,
            'remember_me' => $request->remember_me,
        ];

        return response()->json(
            $this->authService->login($data)
        );
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
     *          @OA\Response(response=200, description="Success",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *          )
     *      ),
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json($result, 201);
    }

    /**
     * @OA\Post(
     *     path="/refresh-token",
     *     summary="Refresh Access Token",
     *     tags={"Auth"},
     *     operationId="refreshToken",
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(ref="#/components/parameters/refresh_token"),
     *
     *     @OA\Response(
     *         response=200,
     *         description="New access token generated",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="1|newtoken123"),
     *             @OA\Property(property="access_expires", type="string", example="2026-04-01 14:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
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
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=400, description="Bad Request")
 * )
 */
public function forgotPassword(Request $request): JsonResponse
{
    $request->validate([
        'email' => 'required|email'
    ]);

        $status = $this->authService->forgotPassword($request->email);

        return response()->json([
            'status' => true,
            'message' => 'Reset link sent successfully'
        ]);
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
 *     @OA\Response(response=200, description="Success"),
 *     @OA\Response(response=400, description="Bad Request")
 * )
 */
public function resetPassword(Request $request): JsonResponse
{
    $request->validate([
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|confirmed|min:6',
    ]);

    $result = $this->authService->resetPasswordWeb($request->all());

    if ($result['status']) {
        return response()->json([
            'status' => true,
            'message' => 'Your password has been changed successfully.'
        ]);
    }

    return response()->json([
        'status' => false,
        'message' => $result['message']
    ], 400);
}

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="User Logout",
     *     tags={"Auth"},
     *     operationId="logoutUser",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        // current token delete karo
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
