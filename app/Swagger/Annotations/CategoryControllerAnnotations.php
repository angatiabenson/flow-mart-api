<?php

namespace App\Swagger\Annotations;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 * name="Categories",
 * description="API Endpoints for Category Management"
 * )
 */
class CategoryControllerAnnotations
{
    /**
     * Store a new product category.
     *
     * @OA\Post(
     *     path="/categories",
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
    public function store()
    {
    }


    /**
     * Fetch all categories for the authenticated user.
     *
     * @OA\Get(
     *     path="/categories",
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
    public function view()
    {
    }
}