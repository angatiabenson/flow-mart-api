<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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
     * Login a user and generate an API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate the request data
        $credentials = $request->only('email', 'password');

        if (Auth::attempt(credentials: $credentials)) {
            // If authentication passes, generate API key (token)
            $user = User::where('email', $credentials['email'])->first();

            // Revoke all existing tokens for the user
            $user->tokens()->delete();

            // Create a new token
            $tokenResult = $user->createToken('api_token');

            // Access the PersonalAccessToken model
            $token = $tokenResult->accessToken;

            // Update the expires_at field
            $token->expires_at = Carbon::now()->addDays(30);
            $token->save();

            // Retrieve the plain text token
            $plainTextToken = $tokenResult->plainTextToken;

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'api_key' => $plainTextToken,
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
     * Retrieve the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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

    /**
     * Update the authenticated user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
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

        // Find the user by ID
        $user = User::find($userID);

        if (!$user) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $userID,
            'phone' => 'sometimes|required|string|max:15|unique:users,phone,' . $userID,
            'password' => 'sometimes|required|string|min:6',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Update the user's name if provided
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        // Update the user's email if provided
        if ($request->has('email')) {
            $user->email = $request->email;
        }

        // Update the user's phone if provided
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        // Update the user's password if provided
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the updated user
        $user->save();

        // Return success response with updated user data
        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'updated_at' => $user->updated_at->toDateTimeString(),
            ]
        ], 200);
    }

    /**
     * Delete the authenticated user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
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

        // Find the user by ID
        $user = User::find($userID);

        if (!$user) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'User not found.'
            ], 404);
        }

        // Revoke all tokens associated with the user
        $user->tokens()->delete();

        // Delete the user account
        $user->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Account deleted successfully.'
        ], 200);
    }
}