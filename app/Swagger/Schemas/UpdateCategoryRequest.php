<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="UpdateCategoryRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Updated name of the category",
 *         example="Home Appliances"
 *     )
 * )
 */

class UpdateCategoryRequest
{

}