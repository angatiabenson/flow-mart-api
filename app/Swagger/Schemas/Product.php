<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     required={"id", "name", "quantity", "category",},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Unique identifier for the product",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the product",
 *         example="Smartphone"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="string",
 *         description="Quantity of the product",
 *         example="50"
 *     ),
 *     @OA\Property(
 *         property="category",
 *         ref="#/components/schemas/Category"
 *     ),
 * )
 */
class Product
{
    // This class is intentionally left blank.
    // It serves as a container for the Product schema.
}