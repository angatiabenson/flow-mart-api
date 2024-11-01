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
    /**
     * Store a new product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            'data' => [
                'message' => 'Product added successfully.'
            ]
        ], 201);
    }

    /**
     * Fetch all products for a specific category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $category_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchProductsByCategory(Request $request, $category_id)
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

        return $this->processProductsResponse($products);
    }


    /**
     * Fetch all products across all categories for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(Request $request)
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

        // Fetch all products that belong to the user's categories
        $products = Product::whereHas('category', function ($query) use ($userID) {
            $query->where('user_id', $userID);
        })->with('category')->get();

        return $this->processProductsResponse($products);
    }

    /**
     * Update an existing product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  Product ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'quantity' => 'sometimes|required|string|max:255',
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

        // Find the product by ID
        $product = Product::find($id);

        // Check if product exists
        if (!$product) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        // Check if the product belongs to the authenticated user via category
        if ($product->category->user_id !== $userID) {
            return response()->json([
                'code' => 403,
                'status' => 'error',
                'message' => 'Forbidden. You do not have permission to update this product.',
            ], 403);
        }

        // If category_id is being updated, verify the new category belongs to the user
        if ($request->has('category_id')) {
            $newCategory = Category::where('id', $request->category_id)
                ->where('user_id', $userID)
                ->first();

            if (!$newCategory) {
                return response()->json([
                    'code' => 403,
                    'status' => 'error',
                    'message' => 'You do not have permission to assign this product to the specified category.',
                ], 403);
            }

            $product->category_id = $request->category_id;
        }

        // Update the product fields if provided
        if ($request->has('name')) {
            $product->name = $request->name;
        }

        if ($request->has('quantity')) {
            $product->quantity = $request->quantity;
        }

        // Save the updated product
        $product->save();

        // Return success response with updated product data
        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Product updated successfully.',
                'product' => $product->load('category'),
            ]
        ], 200);
    }

    /**
     * Delete an existing product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  Product ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $id)
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

        // Find the product by ID
        $product = Product::find($id);

        // Check if product exists
        if (!$product) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        // Check if the product belongs to the authenticated user via category
        if ($product->category->user_id !== $userID) {
            return response()->json([
                'code' => 403,
                'status' => 'error',
                'message' => 'Forbidden. You do not have permission to delete this product.',
            ], 403);
        }

        // Delete the product
        $product->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Product deleted successfully.'
            ]
        ], 200);
    }


    /**
     * Process and format the products' response.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $products
     * @return \Illuminate\Http\JsonResponse
     */
    private function processProductsResponse($products)
    {
        // Format the response to include category details within each product
        $formattedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->quantity,
                'category' => $product->category
            ];
        });

        // Return the formatted response
        return response()->json([
            'status' => 'success',
            'data' => [
                'products' => $formattedProducts
            ]
        ], 200);
    }
}