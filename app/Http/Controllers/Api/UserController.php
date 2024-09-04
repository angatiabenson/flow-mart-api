<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Register a new user
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
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ], 201); // HTTP 201 Created
    }

    // Login user
    public function login(Request $request)
    {
        // Validate the request data
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // If authentication passes, generate API key (token)
            $user = Auth::user();
            $apiKey = $user->createToken('API Token')->plainTextToken;

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'api_key' => $apiKey,
            ], 200);
        }

        // If authentication fails, return error response
        return response()->json([
            'code' => 401,
            'status' => 'error',
            'message' => 'Invalid credentials.',
        ], 401);
    }
}