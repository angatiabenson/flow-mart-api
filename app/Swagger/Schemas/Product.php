<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     required={"id", "name", "quantity", "category_id", "created_at", "updated_at"},
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
 *         property="category_id",
 *         type="integer",
 *         description="ID of the category to which the product belongs",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="category",
 *         ref="#/components/schemas/Category"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the product was created",
 *         example="2023-09-29T12:34:56Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the product was last updated",
 *         example="2023-09-29T12:34:56Z"
 *     )
 * )
 */
class Product
{
    // This class is intentionally left blank.
    // It serves as a container for the Product schema.
}