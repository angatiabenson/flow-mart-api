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