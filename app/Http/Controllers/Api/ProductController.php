<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for Product Management"
 * )
 */

class ProductController extends Controller
{    /**
     * Store a new product under a specific category.
     *
     * @OA\Post(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Create a new product under a specific category",
     *     description="Allows authenticated users to create a new product within a specified category by providing the category ID, product name, and quantity.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Product creation data",
     *         @OA\JsonContent(ref="#/components/schemas/CreateProductRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product added successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=422),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The category_id field is required.")
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
     *         response=403,
     *         description="Forbidden - Permission denied",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=403),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="You do not have permission to add products to this category.")
     *         )
     *     )
     * )
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
            'message' => 'Product added successfully.',
        ], 201);
    }

    /**
     * Get all products by category.
     *
     * @OA\Get(
     *     path="/categories/{category_id}/products",
     *     tags={"Products"},
     *     summary="Retrieve all products under a specific category",
     *     description="Returns a list of all products within the specified category for the authenticated user.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category_id",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved products",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
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
     *         response=403,
     *         description="Forbidden - Permission denied",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=403),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="You do not have permission to view products in this category.")
     *         )
     *     )
     * )
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
     * Fetch all products for the authenticated user.
     *
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Retrieve all products for the authenticated user",
     *     description="Returns a list of all products across all categories owned by the authenticated user.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved products",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
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
     *     )
     * )
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
            'products' => $formattedProducts
        ], 200);
    }
}