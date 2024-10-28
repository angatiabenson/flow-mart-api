<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     type="object",
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the user",
 *         example="Jane Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Email address of the user",
 *         example="jane.doe@example.com"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="Phone number of the user",
 *         example="0987654321"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         description="New password for the account",
 *         example="newpassword123"
 *     )
 * )
 */


class UpdateUserRequest
{

}