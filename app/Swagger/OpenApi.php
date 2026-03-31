<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Referral API",
 *     version="1.0.0"
 * )
 * @OA\SecurityScheme(
 *      securityScheme="Bearer",
 *      type="http",
 *      scheme="bearer"
 * )
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 * @OA\Tag(
 *      name="Auth",
 *      description="Authentification",
 * )
 */
class OpenApi {}