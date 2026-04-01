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
    protected $authService;

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
 *     @OA\Response(
 *         response=200,
 *         description="User login successfully"
 *     )
 * )
 */
public function login(LoginRequest $request): JsonResponse
{
    $result = $this->authService->login($request->validated());

    return response()->json($result);
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
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully"
     *     )
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'status' => true,
            'message' => $result['message'],
            'data' => $result['data'],
        ], 201);
    }
    
     /**
     * @OA\Post(
     *     path="/forgot-password",
     *     summary="Forgot Password",
     *     tags={"Auth"},
     *     operationId="forgotPassword",
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
     *     )
     * )
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $result = $this->authService->forgotPassword($request->email);

        return response()->json($result);
    }

    /**
     * @OA\Post(
     *     path="/reset-password",
     *     summary="Reset Password",
     *     tags={"Auth"},
     *     operationId="resetPassword",
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

        $result = $this->authService->resetPasswordWeb($request->all());

        return response()->json($result);
    }
}
