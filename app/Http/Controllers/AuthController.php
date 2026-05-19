<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;


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
     *      
     *      @OA\Response(response=400, description="Bad Request"),
     *      @OA\Response(response=401, description="Unauthenticated"),
     *      @OA\Response(response=403, description="Forbidden"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      @OA\Response(response=422, description="Unprocessable Entity"),
     *      @OA\Response(response=500, description="Internal Server Error")
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json($result, 201);
    }

    /**
     * @OA\Get(
     *     path="/default-referral",
     *     summary="Get Default Admin Referral Code",
     *     tags={"Auth"},
     *     operationId="defaultReferral",
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function defaultReferral()
    {
        $user = request()->user();

        return response()->json(['referral_code' => $user->referral_code]);
    }

    /**
     * @OA\Post(
     *     path="/refresh-token",
     *     summary="Refresh Access Token",
     *     tags={"Auth"},
     *     operationId="refreshToken",
     *     security={{"Bearer": {}}},
     *
     *     @OA\Parameter(ref="#/components/parameters/refresh_token"),
     *
     *      @OA\Response(response=200, description="Success",
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
     *      @OA\Response(response=200, description="Success",
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
     *      @OA\Response(response=200, description="Success",
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
     * @OA\Get(
     *      path="/resend",
     *      tags={"Auth"},
     *      security={{"Bearer": {}}},
     *      summary="Email Resend",
     *      operationId="Resend",
     *
     *      @OA\Response(response=200, description="Success",
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
    public function resend(): JsonResponse
    {
        if (request()->user()->hasVerifiedEmail()) {

            return response()->json([
                'message' => 'Already Verified'
            ]);
        }

        request()
            ->user()
            ->sendEmailVerificationNotification();

        return response()->json([
            'message' =>
            'Verification email resent successfully.'
        ]);
    }

    /**
     * @OA\Post(
     *      path="/verify-email/{id}/{hash}",
     *      tags={"Auth"},
     *      security={{"Bearer": {}}},
     *      summary="Email Verify",
     *      operationId="userVerify",
     *
     *      @OA\Parameter(ref="#/components/parameters/id"),
     *      @OA\Parameter(ref="#/components/parameters/hash"),
     *      @OA\Parameter(ref="#/components/parameters/expires"),
     *      @OA\Parameter(ref="#/components/parameters/signature"),
     *
     *      @OA\Response(response=200, description="Success",
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
    public function verifyEmail(
        EmailVerificationRequest $request
    ): JsonResponse {
        if (
            request()->user()->hasVerifiedEmail()
        ) {
            return response()->json(['message' => 'Already Verified']);
        }

        if (
            $request->user()->markEmailAsVerified()
        ) {
            $user = $request->user();

            $referrer = User::find($user->referred_by);

            if ($referrer) {
                $referralService = new \App\Services\ReferralService();

                $referralService->distributeLevelIncome($referrer, $user);
            }

            event(new Verified($request->user()));
        }

        return response()->json([
            'message' =>
            'Your email address has been verified successfully!'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="User Logout",
     *     tags={"Auth"},
     *     operationId="logoutUser",
     *     security={{"Bearer": {}}},
     *     @OA\Response(response=200, description="Success",
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
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
