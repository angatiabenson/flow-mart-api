<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Store a new product category
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Get authenticated user
        $user = Auth::user();

        // Create new category
        Category::create([
            'name' => $request->name,
            'user_id' => $user->id,
        ]);

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully.'
        ], 201); // HTTP 201 Created
    }
}