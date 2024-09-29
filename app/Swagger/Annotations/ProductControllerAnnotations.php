<?php

namespace App\Swagger\Annotations;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for Product Management"
 * )
 */
class ProductControllerAnnotations
{
    /**
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
    public function store()
    {
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
    public function fetchProductsByCategory()
    {
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

    public function view()
    {

    }
}