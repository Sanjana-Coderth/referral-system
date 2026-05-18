<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Services\ProfileService;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ChangePasswordRequest;

use OpenApi\Annotations as OA;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * @OA\Get(
     *      path="/profile",
     *      tags={"Profile"},
     *      security={{"Bearer": {}}},
     *      summary="Get User Profile",
     *      operationId="getUserProfile",
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
    public function profile(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => request()->user()
        ]);
    }

    /**
     * @OA\Post(
     *      path="/profile/update",
     *      tags={"Profile"},
     *      security={{"Bearer": {}}},
     *      summary="User Update Profile",
     *      operationId="userUpdateProfile",
     *      
     *      @OA\Parameter(ref="#/components/parameters/name"),
     *      @OA\Parameter(ref="#/components/parameters/usdt_wallet_address"),
     *      @OA\Parameter(ref="#/components/parameters/bsc_wallet_address"),
     *
     *      requestBody={"$ref": "#/components/requestBodies/image"},
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
    public function updateProfile(ProfileUpdateRequest $request): JsonResponse
    {

        $this->profileService->updateProfile(
            $request->validated()
        );

        return response()->json([
            'message' => 'Profile updated successfully.'
        ]);
    }

    /**
     * @OA\Post(
     *      path="/profile/change-password",
     *      tags={"Profile"},
     *      security={{"Bearer": {}}},
     *      summary="User Change Password",
     *      description="Change user password",
     *      operationId="userChangePassword",
     *
     *      @OA\Parameter(ref="#/components/parameters/current_password"),
     *      @OA\Parameter(ref="#/components/parameters/password"),
     *      @OA\Parameter(ref="#/components/parameters/password_confirmation"),
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
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $this->profileService->changePassword(
            $request->validated()
        );

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully.'
        ]);
    }
}
