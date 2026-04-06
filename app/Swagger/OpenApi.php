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
 *     securityScheme="sanctum",
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
 *      name="Dashboard",
 *      description="Dashboard",
 * )
 */
class OpenApi {}