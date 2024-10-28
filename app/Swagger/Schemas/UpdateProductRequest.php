<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="UpdateProductRequest",
 *     type="object",
 *     required={"name", "quantity"},
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         description="ID of the new category the product should belong to",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Updated name of the product",
 *         example="Advanced Smartphone"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="string",
 *         description="Updated quantity of the product",
 *         example="150"
 *     )
 * )
 */

class UpdateProductRequest
{

}