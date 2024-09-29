<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for User Management"
 * )
 */
class UserController extends Controller
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
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Account created successfully.',
            'user' => $user,
        ], 201); // HTTP 201 Created
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
    public function login(Request $request)
    {
        // Validate the request data
        $credentials = $request->only('email', 'password');

        if (Auth::attempt(credentials: $credentials)) {
            // If authentication passes, generate API key (token)
            $user = User::where('email', $credentials['email'])->first();
            $apiKey = $user->createToken('api_token')->plainTextToken;

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'api_key' => $apiKey,
                'user' => $user
            ], 200);
        }

        // If authentication fails, return error response
        return response()->json([
            'code' => 401,
            'status' => 'error',
            'message' => 'Invalid credentials.',
        ], 401);
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
    public function profile(Request $request)
    {
        // Get authenticated user
        $userID = $this->getUserID($request);

        if (!$userID) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 401);
        }

        // Get the user's details
        $user = User::find($userID);

        if (!$user) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        // Return the user details in the desired format
        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'created_at' => $user->created_at->toDateTimeString(),
            ]
        ], 200);
    }
}