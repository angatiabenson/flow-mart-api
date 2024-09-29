<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="FlowMart API",
 *      description="FlowMart API is a RESTful API designed to help SMEs manage their stock efficiently. It allows users to create accounts, manage product categories, and add and view products by category. The API is built using the Laravel framework and is secured with session-based API keys.",
 *      @OA\Contact(name="Swagger API Team")
 * )
 *
 * @OA\Server(
 *      url="https://flowmart.banit.co.ke/",
 *      description="Live Server"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="BearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Enter your Bearer token in the format **Bearer <token>**"
 * )
 */
class OpenApi
{
    // This class is intentionally left blank.
    // It serves as a container for OpenAPI annotations.
}