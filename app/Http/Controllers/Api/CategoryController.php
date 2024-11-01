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
            'data' => [
                'message' => 'Category created successfully.'
            ]
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
            'data' => [
                'categories' => $categories
            ]
        ], 200);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
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

        // Find the category by ID
        $category = Category::find($id);

        // Check if category exists
        if (!$category) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Category not found.'
            ], 404);
        }

        // Check if the category belongs to the authenticated user
        if ($category->user_id !== $userID) {
            return response()->json([
                'code' => 403,
                'status' => 'error',
                'message' => 'Forbidden. You do not have permission to update this category.'
            ], 403);
        }

        // Update the category
        $category->name = $request->name;
        $category->save();

        // Return success response with updated category data
        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Category updated successfully.',
                'category' => $category
            ]
        ], 200);
    }

    /**
     * Delete the specified category from storage.
     */
    public function delete(Request $request, $id)
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

        // Find the category by ID
        $category = Category::find($id);

        // Check if category exists
        if (!$category) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Category not found.'
            ], 404);
        }

        // Check if the category belongs to the authenticated user
        if ($category->user_id !== $userID) {
            return response()->json([
                'code' => 403,
                'status' => 'error',
                'message' => 'Forbidden. You do not have permission to delete this category.'
            ], 403);
        }

        // Delete the category
        $category->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Category deleted successfully.'
            ]
        ], 200);
    }
}