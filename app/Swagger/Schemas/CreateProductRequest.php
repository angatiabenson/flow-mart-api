<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="CreateProductRequest",
 *     type="object",
 *     required={"category_id", "name", "quantity"},
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         description="ID of the category under which the product is to be added",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         maxLength=255,
 *         description="Name of the product",
 *         example="Smartphone"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="string",
 *         maxLength=255,
 *         description="Quantity of the product",
 *         example="50"
 *     )
 * )
 */
class CreateProductRequest
{
    // This class is intentionally left blank.
    // It serves as a container for the CreateProductRequest schema.
}