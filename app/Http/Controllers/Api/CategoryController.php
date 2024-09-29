<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

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
        $userID = $this->getUserID($request);

        if ($userID == null) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 401);
        }

        // Create new category
        Category::create([
            'name' => $request->name,
            'user_id' => $userID,
        ]);

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully.'
        ], 201); // HTTP 201 Created
    }

    // Fetch all categories for the authenticated user
    public function view(Request $request)
    {
        // Get authenticated user
        $userID = $this->getUserID($request);

        if ($userID == null) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 401);
        }

        // Fetch all categories that belong to the user
        $categories = Category::where('user_id', $userID)->get();

        // Return the categories in the desired format
        return response()->json([
            'status' => 'success',
            'categories' => $categories
        ], 200);
    }
}