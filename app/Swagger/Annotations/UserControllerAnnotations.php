<?php

namespace App\Swagger\Annotations;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for User Management"
 * )
 */
class UserControllerAnnotations
{
    /**
     * Register a new user.
     *
     * @OA\Post(
     *     path="/register",
     *     tags={"Users"},
     *     summary="Register a new user",
     *     description="Creates a new user account with the provided information.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Account created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Account created successfully."),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=422),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The email has already been taken.")
     *         )
     *     )
     * )
     */
    public function register()
    {
    }

    /**
     * Login user.
     *
     * @OA\Post(
     *     path="/login",
     *     tags={"Users"},
     *     summary="Login user",
     *     description="Authenticates user and returns an API key.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="User login credentials",
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful."),
     *             @OA\Property(property="api_key", type="string", example="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=401),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Invalid credentials.")
     *         )
     *     )
     * )
     */
    public function login()
    {
    }
    /**
     * Get the authenticated user's profile details.
     *
     * @OA\Get(
     *     path="/profile",
     *     tags={"Users"},
     *     summary="Get user profile",
     *     description="Returns the authenticated user's profile details.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User profile details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="user",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized action",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=401),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized action.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=404),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     )
     * )
     */
    public function profile()
    {
    }
}