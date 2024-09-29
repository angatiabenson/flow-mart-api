<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     type="object",
 *     required={"email", "password"},
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="User's email address",
 *         example="johndoe@example.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         description="User's password",
 *         example="securepassword"
 *     )
 * )
 */
class LoginRequest
{
    // This class is intentionally left blank.
    // It serves as a container for the LoginRequest schema.
}