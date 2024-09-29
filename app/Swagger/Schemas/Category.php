<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     title="Category",
 *     required={"id", "name"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the category",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the product category",
 *         example="Electronics"
 *     )
 * )
 */
class Category
{
    // This class is intentionally left blank.
    // It serves as a container for the Category schema.
}