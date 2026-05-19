<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Referral API",
 *     version="1.0.0"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="Bearer",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\Tag(
 *     name="Auth",
 *     description="Authentication"
 * )
 * 
 * @OA\Tag(
 *     name="Wallet",
 *     description="Wallet"
 * )
 * 
 * @OA\Tag(
 *     name="Referral",
 *     description="Referral"
 * )
 * 
 * @OA\Tag(
 *     name="Dashboard",
 *     description="Dashboard"
 * )
 *
 * @OA\Parameter(
 *     parameter="id",
 *     name="id",
 *     in="path",
 *     required=true,
 *     description="User ID",
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="hash",
 *     name="hash",
 *     in="path",
 *     required=true,
 *     description="Verification Hash",
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="expires",
 *     name="expires",
 *     in="query",
 *     required=true,
 *     description="Expiration Timestamp",
 *     @OA\Schema(type="string")
 * )
 *
 * @OA\Parameter(
 *     parameter="signature",
 *     name="signature",
 *     in="query",
 *     required=true,
 *     description="Verification Signature",
 *     @OA\Schema(type="string")
 * )
 * 
 * @OA\Parameter(
 *     parameter="default_referral_code",
 *     name="referral_code",
 *     in="query",
 *     required=false,
 *     description="Default Admin Referral Code",
 * )
 */
class OpenApi {}