<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     type="object",
 *     required={"name", "email", "phone", "password"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         description="User's full name",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         maxLength=255,
 *         description="User's email address",
 *         example="johndoe@example.com"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         maxLength=15,
 *         description="User's phone number",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         minLength=6,
 *         description="User's password",
 *         example="securepassword"
 *     )
 * )
 */
class RegisterRequest
{
    // This class is intentionally left blank.
    // It serves as a container for the RegisterRequest schema.
}