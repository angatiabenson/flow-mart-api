<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     required={"id", "name", "email", "phone", "created_at"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the user",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="User's full name",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="User's email address",
 *         example="johndoe@example.com"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="User's phone number",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the user was created",
 *         example="2023-09-29T12:34:56Z"
 *     )
 * )
 */
class User
{
    // This class is intentionally left blank.
    // It serves as a container for the User schema.
}