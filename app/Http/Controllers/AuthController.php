<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="test@gmail.com"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="remember_me", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="User login successfully")
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return response()->json(
            $this->authService->login($request->validated())
        );
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="User Register",
     *     tags={"Auth"},
     *     operationId="registerUser",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string", example="Priyanka"),
     *             @OA\Property(property="email", type="string", example="test@gmail.com"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="password_confirmation", type="string", example="123456"),
     *             @OA\Property(property="referred_by_code", type="string", example="9AEC25AB")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered successfully")
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
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="refresh_token",
 *                 type="string",
 *                 example="1|abcdef123456"
 *             )
 *         )
 *     ),
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
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", example="test@gmail.com")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reset link sent successfully"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
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
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","token","password","password_confirmation"},
 *             @OA\Property(property="email", type="string", example="test@gmail.com"),
 *             @OA\Property(property="token", type="string", example="reset-token-here"),
 *             @OA\Property(property="password", type="string", example="123456"),
 *             @OA\Property(property="password_confirmation", type="string", example="123456")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset successful"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
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
