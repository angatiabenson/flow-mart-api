<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="CreateCategoryRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         description="Name of the product category",
 *         example="Electronics"
 *     )
 * )
 */
class CreateCategoryRequest
{
    // This class is intentionally left blank.
    // It serves as a container for the CreateCategoryRequest schema.
}