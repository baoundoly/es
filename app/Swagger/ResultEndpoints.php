<?php

namespace App\Swagger;

/**
 * @OA\Tag(
 *   name="Results",
 *   description="Result APIs"
 * )
 *
 * @OA\Get(
 *   path="/api/results",
 *   tags={"Results"},
 *   summary="Get active result list",
 *   @OA\Response(
 *     response=200,
 *     description="Result list",
 *     @OA\JsonContent(
 *       @OA\Property(property="success", type="boolean", example=true),
 *       @OA\Property(property="message", type="string"),
 *       @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Result")
 *       ),
 *       @OA\Property(property="count", type="integer", example=3)
 *     )
 *   ),
 *   @OA\Response(response=401, description="Unauthenticated")
 * )
 */
final class ResultEndpoints
{
}
