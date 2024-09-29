<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Categories",
 *     description="API Endpoints for Category Management"
 * )
 */
class CategoryController extends Controller
{
    /**
     * Store a new product category.
     *
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Create a new product category",
     *     description="Allows authenticated users to create a new product category by providing a unique name.",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Category creation data",
     *         @OA\JsonContent(ref="#/components/schemas/CreateCategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Category created successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=422),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The name has already been taken.")
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


    /**
     * Fetch all categories for the authenticated user.
     *
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Retrieve all product categories for the authenticated user",
     *     description="Returns a list of all product categories created by the authenticated user.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved categories",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Category")
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