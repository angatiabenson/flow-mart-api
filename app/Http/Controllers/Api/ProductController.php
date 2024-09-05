<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // Store a new product under a specific category
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|string|max:255',
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

        if (!$userID) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 401);
        }
        $category = Category::where('id', $request->category_id)
            ->where('user_id', $userID)
            ->first();

        if (!$category) {
            return response()->json([
                'code' => 403,
                'status' => 'error',
                'message' => 'You do not have permission to add products to this category.',
            ], 403);
        }

        // Create the new product
        Product::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
        ]);

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Product added successfully.',
        ], 201);
    }


    // Get all products by category
    public function fetchProductsByCategory(Request $request, $category_id)
    {
        // Check if the category belongs to the authenticated user
        // Get authenticated user
        $userID = $this->getUserID($request);

        if (!$userID) {
            return response()->json([
                'code' => 401,
                'status' => 'error',
                'message' => 'Unauthorized action.'
            ], 401);
        }

        $category = Category::where('id', $category_id)
            ->where('user_id', $userID)
            ->first();

        // If the category does not exist or doesn't belong to the user
        if (!$category) {
            return response()->json([
                'code' => 403,
                'status' => 'error',
                'message' => 'You do not have permission to view products in this category.',
            ], 403);
        }

        // Get all products for the category
        $products = Product::where('category_id', $category_id)->get();

        // Format the response to include category details within each product
        $formattedProducts = $products->map(function ($product) use ($category) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->quantity,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name
                ]
            ];
        });

        // Return the formatted response
        return response()->json([
            'status' => 'success',
            'products' => $formattedProducts
        ], 200);
    }
}